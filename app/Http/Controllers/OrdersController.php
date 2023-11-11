<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\ItemsUsed;
use App\Models\OrderItem;
use App\Models\Outfit;
use App\Models\Payment;
use App\Models\OutfitsOrders;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use App\Notifications\OrderProcessed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::getAll();
        $orders_sum = $orders->sum('total_amount');
        $orders_count = $orders->count();

        $view = 'pages.orders.index';
        return view($view)->with(compact('orders','orders_sum','orders_count'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->forget(['customer_id', 'outfit_orders']);
        $view = 'pages.orders.create.step1';
        return view($view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate customer entry, store and set view to list of customers
        $validatedData = $request->validate([
            'customer_id' => ['required'],
            'total_amount' => ['required'],
        ]);

        $user_account_settings = auth()->user()->user_account->app_settings;

        $vat = $user_account_settings->vat/100 * $request->total_amount;
        
        $order = new Order;
        $order->customer_id = $request->customer_id;
        $order->total_amount = $request->total_amount; 
        $order->vat = $vat; 
        $order->order_type = $request->order_type;
        $order->user_id = $user_account_settings->user_id;
        $order->status = 'Pending Payment';
        if($request->order_type == 'tailoring'){
            $order->expected_delivery_date = $request->expected_delivery_date;
        }

        $order->save();

        $order->created_at = $request->created_at;
        $order->save();

        if($request->order_type == 'tailoring'){
            //$order->instruction = session('order_style');
           if(session()->has('outfit_orders')){
            foreach(session('outfit_orders') as $key => $outfit){
                $outfit_order = new OutfitsOrders;
                $outfit_order->order_id = $order->id;
                $outfit_order->user_id = $user_account_settings->user_id;

                $titles = explode('<?>',$outfit);
                $outfit_order->name = $titles[0];
                $outfit_order->price = $titles[1];
                $outfit_order->instruction = $titles[2];
                $outfit_order->qty = $titles[3];
                $outfit_order->image = $titles[4];
                if($outfit_order->image == ''){
                    $outfit_order->image = 'default.jpg';
                }

                $outfit_order->save();
            }
           }
            
        }

        $request->session()->forget(['customer_id', 'outfit_orders']);

        //create invoice as well
        $invoice = new Invoice;
        $invoice->order_id = $order->id;
        $invoice->payment_status = 'Pending Payment';
        $invoice->save();

        //send sms
        if($request->order_type == 'tailoring'){
            $customer = Customer::findOrFail($request->customer_id);
            $api_key = $user_account_settings->sms_api_key;
            $username = $user_account_settings->sms_api_username;
            $sender = $user_account_settings->sms_sender;
			$business_name = $user_account_settings->business_name;
            $msg = 'Thank you for your order. Your expected fitting date is '. $order->expected_delivery_date .'. Thank you for choosing '.$business_name;
            $request_url = 'https://api.ebulksms.com:4433/sendsms?username='.$username.'&apikey='.$api_key.'&sender='.$sender.'&messagetext='.$msg.'&flash=0&recipients='.$customer->phone;
            $sms_response = "";
            if ($order->order_type == "tailoring"){
                //$sms_response = Http::get($request_url);
                //$customer->notify(new OrderProcessed($order));
                if(auth()->user()->user_account->isPremiumUser()){
                    //sendwhatsappnotification("new_order",$customer->whatsappNumber(),"new_order_1",$order->expected_delivery_date,$order->id);
                    if($user_account_settings->business_currency == "NGN"){
                        //$sms_response = Http::get($request_url);
                    }
                }
                
            }
        }

        if($request->order_type == 'sales'){
            //add items in cart to items_order table
            $items = session()->get('cart_items');
            foreach ($items as $key => $item) 
            {
                $order_item = new OrderItem;
                $order_item->order_id = $order->id;
                $order_item->item_id = $item->id;
                $order_item->qty = 1;
                $order_item->amount = $item->price;

                $order_item->save();

                //reduce each item from inventory
                $_item = Item::find($item->id);
                $_item->inventory_quantity--;
                $_item->save();
            }
            
            //empty cart
            session()->pull('cart_items');
            session()->pull('customer_id');
            session()->pull('cart_total_amount');
            

            return redirect('/payments/create-by-invoice?invoice_id='.$invoice->id)->with('status', 'Order was created successfully. You can add Payment if any. ');
        }
        
        
        return redirect('/orders/'.$order->id)->with('status', 'Order was created successfully. You can add Payment if any. ');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        
        $view = 'pages.orders.show';
        return view($view)->with('order',$order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $order = Order::find($id);
        
        $order->status = $request->status;
        $order->total_amount = $request->total_amount; 
        $order->created_at = $request->created_at;
        $total_amount = 0;
        if($request->order_type == 'tailoring'){
            $order->expected_delivery_date = $request->expected_delivery_date;
            $order->instructions = $request->instructions;
           
            
			if($request->outfit){
				foreach($request->outfit as $key => $outfit){
                    
                    $outfit_order = OutfitsOrders::find($outfit);
                    if($outfit_order){
                        $outfit_order->staff_id = $request->staff_id[$key];
                        $outfit_order->tailor_cost = $request->tailor_cost[$key];
                        $outfit_order->material_cost = $request->material_cost[$key];
                        $outfit_order->customer_id = $request->customer_id[$key];
                        $outfit_order->job_status = $request->job_status[$key];
                    }
                    else{
                        $outfit_order = new OutfitsOrders;
                        $outfit_order->user_id = auth()->user()->user_account_id;
                        $outfit_order->order_id = $order->id;
                    }

                    $instruction = $request->instruction[$key];
                    $qty = $request->qty[$key];
                    if($instruction == ''){
                        $instruction = '-';
                    }
                    if($qty == ''){
                        $qty = 1;
                    }
                    
                    $outfit_order->name = $request->name[$key];
                    $outfit_order->price = $request->price[$key];
                    $outfit_order->qty = $qty;
                    $outfit_order->instruction = $instruction;

                    if($request->file('styles')){
                        $allowedfileExtension=['pdf','jpg','png','jpeg'];
                        foreach ($request->file('styles') as $key_ => $file){
                            if($key == $key_){
                                $filename = $file->getClientOriginalName();
                                $extension = $file->getClientOriginalExtension();
                                $check=in_array($extension,$allowedfileExtension);
                                
                                if($check)
                                {              
                                    $newfilename = time().rand(111, 9999).".". $extension;
                                    FacadesStorage::disk('styles')->put($newfilename, file_get_contents($file));
                                    $outfit_order->image = $newfilename;
                                }
                            }
                        }
                        
                    }
                    $amount = $outfit_order->qty * $outfit_order->price;
                    $total_amount += $amount;
                    $outfit_order->save();
                }
			}
            $order->total_amount = $total_amount;
        }
        
        $order->save();
        
        return redirect('orders/'.$order->id)->with('status', 'Order was updated successfully');
    }

    public function filter(Request $request){        
        $from = $request->from_filter;
        $to = $request->to_filter;
        $search_by = $request->search_by;
  
        $order_query = auth()->user()->user_account->orders()->when($request->query('search_by'), fn(Builder $query, $status) => $query->where('status', $status))
        ->where('expected_delivery_date','>=',$from)->where('expected_delivery_date','<=',$to);

        $orders_sum = $order_query->sum('total_amount');
        $orders_count = $order_query->count();

        $orders = $order_query->paginate(15)->appends([
                'from' => request('from'),
                'search_by' => request('search_by'),
                'to' => request('to'),
                'orders_sum' => $orders_sum,
                'orders_count' => $orders_count,
                ]);

        return view('pages.orders.index')->with(compact('orders','from','to','search_by','orders_sum','orders_count'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        
        //get the invoice for that order
        $invoice = Invoice::where('order_id',$order->id)->first();
        //get all the payments for that invoice
        if($invoice){
            Payment::where('invoice_id',$invoice->id)->delete();
            //delete all the payments
            //delete the invoice
            $invoice->delete();
            //delete the order
        }
        
        
        $order->delete();
        return redirect('orders')->with('status', 'Order was deleted successfully');
    }

    public function addStyleToOrder(Request $request){
        $total_amount_less_vat = 0;
        if (!session()->has('outfit_orders')){
            session()->put('outfit_orders', []);
        }
        if($request->style_names)
        {
            $allowedfileExtension=['pdf','jpg','png','jpeg'];
            foreach ($request->style_names as $key_=>$style){
                $instruction = $request->instruction[$key_];
                $qty = $request->qty[$key_];
                if($instruction == ''){
                    $instruction = '-';
                }
                if($qty == ''){
                    $qty = 1;
                }
                $style_and_image_string = $style.'<?>'.$request->price[$key_].'<?>'.$instruction.'<?>'.$qty.'<?>';
                $amount = $request->price[$key_] * $qty;
                $total_amount_less_vat += $amount;
                if($request->file('styles')){
                    foreach ($request->file('styles') as $key => $file){
                        if($key == $key_){
                            $filename = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $check=in_array($extension,$allowedfileExtension);
                            
                            if($check)
                            {              
                                $newfilename = time().rand(111, 9999).".". $extension;
                                FacadesStorage::disk('styles')->put($newfilename, file_get_contents($file));
                                $style_and_image_string .= $newfilename;
                                $file_uploaded = 1;
                            }
                        }
                    }
                    
                }
                else{
                    $style_and_image_string .= 'default.jpg';
                }
                session()->push('outfit_orders', $style_and_image_string);
            }               
        }
        
        $customer = Customer::find(session('customer_id'));
        $total_amount = $total_amount_less_vat;
        
        $view = 'pages.orders.create.tailoring.step4';
        return view($view)->with(compact('customer','total_amount'));
    }

    public function addItemToCart($item_id)
    {   
        if (!session()->has('cart_items')){
            session()->put('cart_items', []);
            session()->put('cart_total_amount', 0);
        }
        $item = Item::find($item_id);
        session()->push('cart_items', $item);

        $total_amount  = session()->get('cart_total_amount');
        $total_amount += $item->price;
        session(['cart_total_amount' => $total_amount]);

        return session('cart_items');
    }
    public function removeItemFromCart($item_id)
    {   
        if (session()->has('cart_items'))
        {
            $items = session()->get('cart_items');
            $total_amount  = session()->get('cart_total_amount');
            foreach ($items as $key => $item) 
            {
                if ($item->id == $item_id)
                {
                    unset($items[$key]);
                    $total_amount -= $item->price;
                    break;
                }
            }
            session(['cart_items' => $items]);
            session(['cart_total_amount' => $total_amount]);
        }
        return session('cart_items');
    }

    public function addItemsUsed(Request $request){
        $item_used = ItemsUsed::create([
            'outfits_orders_id' => $request->outfit_order_id,
            'item_id' => $request->item_id,
            'qty' => $request->qty,
            'unit_cost' => $request->unit_cost,
            'amount' => $request->amount,
        ]);
        //reduce in inventory
        $item = Item::find($item_used->item_id);
        $item->inventory_quantity -= $item_used->qty;
        $item->save();
        return redirect('orders/'.$item_used->outfit->order->id)->with('status', 'Added Successfully');
    }

}
