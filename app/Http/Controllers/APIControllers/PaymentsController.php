<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Invoice;

class PaymentsController extends Controller
{

    public function index()
    {
        $payments = Payment::where('user_id',auth()->user()->id)->orderBy('created_at','desc')->paginate(10);
        return response()->json($payments,200);
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
        $payment->user_id = auth()->user()->user_account_id;
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
        
        return response('Success',200);
    }

    public function search(Request $request)
    {
        $view = auth()->user()->user_type;
        $payments = Payment::where('user_id',auth()->user()->user_account_id)
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

        return response()->json(null, 204);
    }

    
}
