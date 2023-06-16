<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Invoice;
use Auth;

class PaymentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::where('user_id',Auth::user()->id)->orderBy('created_at','desc')->paginate(10);
        $view = Auth::user()->user_type . '.payments.index';
        return view($view)->with('payments',$payments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => ['required'],
        ]);
        $invoice = Invoice::where('order_id',"{$request->order_id}")->first();
        $view = Auth::user()->user_type . '.payments.create';
        return view($view)->with('invoice',$invoice);
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
            'invoice_id' => ['required'],
        ]);
        
        $payment = new Payment;
        $payment->user_id = Auth::user()->id;
        $payment->invoice_id = $request->invoice_id;
        $payment->amount = $request->amount;
        $payment->mode_of_payment = $request->mode_of_payment;
        $payment->notes = $request->notes;

        //turn order status to processing or completed if for sale
        $invoice = Invoice::find($request->invoice_id);
        $order = Order::find($invoice->order_id);

        $status = "Processing";
        if($order->order_type == "sales"){
            $status = "Completed";
        }

        $affected = DB::update('update orders set status = ? where id = ?',[$status, $order->id]);

        $payment->save();
        
        return redirect('orders')->with('status', 'Payment was added successfully');
    }

    public function search(Request $request)
    {
        $view = Auth::user()->user_type;
        $payments = Payment::where('user_id',Auth::user()->id)
        ->where('created_at', '>=', "{$request->start_date}")
        ->where('created_at', '<=', "{$request->end_date}")
        ->paginate(20)->appends([
            'start_date' => request('start_date'),
            'end_date' => request('end_date'),
            ]);
        if(count($payments)>0){
            return view($view.'.payments.index')->with('payments', $payments);
        }
        else{
            return redirect('payments')->withFail('No Payment found for the selected dates');
        }
        
    }

    /** 
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::findOrFail($id);
        if(Auth::user()->user_type == 'admin'){
            return view('admin.items.show')->with('item',$item);
        }
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
        $validatedData = $request->validate([
            'customer_id' => ['required'],
        ]);
        
        $measurement = Measurement::find($id);
        $measurement->bust = $request->bust;
        $measurement->waist1 = $request->waist1;
        $measurement->waist2 = $request->waist2;
        $measurement->customer_id = $request->customer_id;

        $measurement->save();

        if($request->origin == 'order_creation'){
            return view('admin.orders.create.tailoring.step3');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);

        $invoice = Invoice::find($payment->invoice_id);
        $order = Order::find($invoice->order_id);

        //delete the payment
        $payment->delete();

        return redirect('orders/'.$order->id)->with('status', 'Payment was deleted successfully');
    }

    
}
