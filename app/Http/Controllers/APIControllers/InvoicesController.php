<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PrintController;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Customer;
use App;

class InvoicesController extends Controller
{
    function printInvoice($invoice_id){
        $invoice = Invoice::findOrFail($invoice_id);
        $order = Order::findOrFail($invoice->order_id);
        $customer = Customer::findOrFail($order->customer_id);

        $printController = new PrintController();
        return $printController->printInvoice($invoice, $customer, $order);
        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML($this->a4html);
        // return $pdf->stream();
    }
    
}
