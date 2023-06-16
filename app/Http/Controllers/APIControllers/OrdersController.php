<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\Payment;

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
        $view = 'pages.orders.index';
        return view($view)->with('orders',$orders);
    }
    
    public function search(Request $request)
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        
        $order = new Order;
        $order->customer_id = $request->customer_id;
        $order->total_amount = $request->total_amount; 
        $order->order_type = $request->order_type;
        $order->user_id = auth()->user()->id;
        
        $order->status = 'Pending Payment';

        if($request->order_type == 'tailoring'){
            $order->expected_delivery_date = $request->expected_delivery_date;
            $order->order_style = session('order_style');
            $order->order_style_images = session('order_style_images');
        }
    
        $order->save();
        $request->session()->forget(['customer_id', 'order_style']);

        //create invoice as well
        $invoice = new Invoice;
        $invoice->order_id = $order->id;
        $invoice->payment_status = 'Pending Payment';
        $invoice->save();

        //send sms
        if($request->order_type == 'tailoring'){
            $customer = Customer::findOrFail($request->customer_id);
            $api_key = 'a13babcd7b8dea714c3454f865f97d36ab76fbde';
            $username = 'fortubeks2010@hotmail.com';
            $sender = 'Fdees';
            $msg = 'Thank you for your order. Your expected fitting date is '. $order->expected_delivery_date .'Thank you for choosing Fdees Fashion House';
            $request_url = 'http://api.ebulksms.com:8080/sendsms?username='.$username.'&apikey='.$api_key.'&sender='.$sender.'&messagetext='.$msg.'&flash=0&recipients='.$customer->phone;
            $sms_response = "";
            if ($order->order_type == "tailoring"){
                //$sms_response = Http::get($request_url);
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
            

            return $order;
        }
        
        
        return $order;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        return $order;
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
        if($request->order_type == 'tailoring'){
            $order->expected_delivery_date = $request->expected_delivery_date;
            $order->order_style = $request->order_style;

            $style_images = '';
            if($request->hasFile('styles'))
            {
                $allowedfileExtension=['pdf','jpg','png','jpeg'];

                foreach($request->file('styles') as $key=>$file)
                {
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $check=in_array($extension,$allowedfileExtension);
                    
                    if($check)
                    {              
                            $newfilename = $file->store('styles');
                            //
                            $style_images .= $request->style_names[$key].'='.$newfilename.',';    
                    }
                
                }
                $style_images = substr($style_images, 0, -1);
                
            }
            $order->order_style_images .= ','. $style_images;
        }
    
        $order->save();
        
        return redirect('orders')->with('status', 'Order was updated successfully');
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
        Payment::where('invoice_id',$invoice->id)->delete();
        //delete all the payments
        //delete the invoice
        $invoice->delete();
        //delete the order
        
        $order->delete();
        return redirect('orders')->with('status', 'Order was deleted successfully');
    }

    public function addStyleToOrder(Request $request){
        $style_images = '';
        if($request->hasFile('styles'))
        {
            $allowedfileExtension=['pdf','jpg','png','jpeg'];

            foreach($request->file('styles') as $key=>$file)
            {
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $check=in_array($extension,$allowedfileExtension);
                
                if($check)
                {              
                        $newfilename = $file->store('styles');
                        //
                        $style_images .= $request->style_names[$key].'='.$newfilename.',';    
                }
            
            }
            $style_images = substr($style_images, 0, -1);
            
        }
        session(['order_style' => $request->style]);
        session(['order_style_images' => $style_images]);
        $customer = Customer::find(session('customer_id'));
        
        $view = auth()->user()->user_type . '.orders.create.tailoring.step4';
        return view($view)->with('customer',$customer);
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

    public function getOrdersDueThisWeek()
    {
        $orders = Order::orderBy('expected_delivery_date','asc')->get();
        return $orders;
    }
}
