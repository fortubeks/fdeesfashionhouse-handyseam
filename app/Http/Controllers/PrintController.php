<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use App\Models\OutfitsOrders;
use App\Models\Setting;
use Illuminate\Support\Facades\App as FacadesApp;

class PrintController extends Controller
{
    function printThermalInvoice($order_id){
        $order = Order::findOrFail($order_id);
		return view('pages.orders.print-invoice')->with('order', $order);
    }

    function printMeasurementAndInstruction($outfit_id){
        $outfit = OutfitsOrders::find($outfit_id);
        $order = $outfit->order;
		return view('pages.orders.print-measurement-inst')->with(compact('outfit','order'));
    }

    function printMeasurementPdf($customer_id){
        $pdf = FacadesApp::make('dompdf.wrapper');
        $setting = Setting::where('user_id', auth()->user()->user_account_id)->first();
        $image = $setting->business_logo ?? 'handyseam_logo.png';
        $logo = asset('/storage/logo_images/'.$image);
        $customer = Customer::find($customer_id);
        $customer_measurement_details = json_decode($customer->measurement_details, true);
        $measurementString = '<!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8" />
                <title>Measurement For Customer</title>
        
                <style>
                    .invoice-box {
                        max-width: 800px;
                        margin: auto;
                        padding: 20px;
                        border: 1px solid #eee;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
                        font-size: 14px;
                        line-height: 24px;
                        font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;
                        color: #555;
                    }
        
                    .invoice-box table {
                        width: 100%;
                        line-height: inherit;
                        text-align: left;
                    }
        
                    .invoice-box table td {
                        padding: 5px;
                        vertical-align: top;
                    }
        
                    .invoice-box table tr td:nth-child(2) {
                        text-align: right;
                    }
        
                    .invoice-box table tr.top table td {
                        padding-bottom: 10px;
                    }
        
                    .invoice-box table tr.top table td.title {
                        font-size: 35px;
                        line-height: 35px;
                        color: #333;
                    }
        
                    .invoice-box table tr.information table td {
                        padding-bottom: 20px;
                    }
        
                    .invoice-box table tr.heading td {
                        background: #eee;
                        border-bottom: 1px solid #ddd;
                        font-weight: bold;
                    }
        
                    .invoice-box table tr.details td {
                        padding-bottom: 10px;
                    }
        
                    .invoice-box table tr.item td {
                        border-bottom: 1px solid #eee;
                    }
        
                    .invoice-box table tr.item.last td {
                        border-bottom: none;
                    }
        
                    .invoice-box table tr.total td:nth-child(2) {
                        border-top: 2px solid #eee;
                        font-weight: bold;
                    }
        
                    @media only screen and (max-width: 600px) {
                        .invoice-box table tr.top table td {
                            width: 100%;
                            display: block;
                            text-align: center;
                        }
        
                        .invoice-box table tr.information table td {
                            width: 100%;
                            display: block;
                            text-align: center;
                        }
                    }
        
                    /** RTL **/
                    .invoice-box.rtl {
                        direction: rtl;
                        font-family: Tahoma, \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;
                    }
        
                    .invoice-box.rtl table {
                        text-align: right;
                    }
        
                    .invoice-box.rtl table tr td:nth-child(2) {
                        text-align: left;
                    }
                </style>
            </head>
        
            <body>
                <div class="invoice-box">
                    <table cellpadding="0" cellspacing="0">
                        <tr class="top">
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td class="title">
                                            <img src="'.$logo.'" style="width: 100%; max-width: 300px" />
                                        </td>
                                        <td>
                                        Measurement Details For<br />
                                        '.$customer->name.' <br />
                                        
                                    </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
        
                        <tr class="heading">
                            <td>Measurement</td>
        
                            <td>Value</td>
                        </tr>
                        ';
                        foreach($customer_measurement_details as $key => $measurement_detail)
                        {
                            $measurementString .= '<tr class="item">
                            <td>'.$key.'</td>
                            <td>'.$measurement_detail.'</td>
                            </tr>';
                        }

                    $measurementString .= '</table>
                </div>
            </body>
        </html>';
        $pdf->loadHTML($measurementString);
        return $pdf->stream();
    }

    function printInvoice($invoice, $customer, $order){
        $pdf = FacadesApp::make('dompdf.wrapper');
        $setting = Setting::where('user_id', auth()->user()->user_account_id)->first();
        $image = $setting->business_logo ?? 'handyseam_logo.png';
        $logo = asset('/storage/logo_images/'.$image);
        $payment_qr = '';
        if($setting->payment_qr){
            $payment_qr = asset('/storage/logo_images/'.$setting->payment_qr);
        }
        $invoiceString = '<!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8" />
                <title>Invoice For Customer</title>
        
                <style>
                    .invoice-box {
                        max-width: 800px;
                        margin: auto;
                        padding: 30px;
                        border: 1px solid #eee;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
                        font-size: 16px;
                        line-height: 24px;
                        font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;
                        color: #555;
                    }
        
                    .invoice-box table {
                        width: 100%;
                        line-height: inherit;
                        text-align: left;
                    }
        
                    .invoice-box table td {
                        padding: 5px;
                        vertical-align: top;
                    }
        
                    .invoice-box table tr td:nth-child(2) {
                        text-align: right;
                    }
                    .invoice-box table tr td:nth-child(3) {
                        text-align: right;
                    }
                    .invoice-box table tr td:nth-child(4) {
                        text-align: right;
                    }
        
                    .invoice-box table tr.top table td {
                        padding-bottom: 20px;
                    }
        
                    .invoice-box table tr.top table td.title {
                        font-size: 45px;
                        line-height: 45px;
                        color: #333;
                    }
        
                    .invoice-box table tr.information table td {
                        padding-bottom: 40px;
                    }
        
                    .invoice-box table tr.heading td {
                        background: #eee;
                        border-bottom: 1px solid #ddd;
                        font-weight: bold;
                    }
        
                    .invoice-box table tr.details td {
                        padding-bottom: 20px;
                    }
        
                    .invoice-box table tr.item td {
                        border-bottom: 1px solid #eee;
                    }
        
                    .invoice-box table tr.item.last td {
                        border-bottom: none;
                    }
        
                    .invoice-box table tr.total td:nth-child(2) {
                        border-top: 2px solid #eee;
                        font-weight: bold;
                    }
        
                    @media only screen and (max-width: 600px) {
                        .invoice-box table tr.top table td {
                            width: 100%;
                            display: block;
                            text-align: center;
                        }
        
                        .invoice-box table tr.information table td {
                            width: 100%;
                            display: block;
                            text-align: center;
                        }
                    }
        
                    /** RTL **/
                    .invoice-box.rtl {
                        direction: rtl;
                        font-family: Tahoma, \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;
                    }
        
                    .invoice-box.rtl table {
                        text-align: right;
                    }
        
                    .invoice-box.rtl table tr td:nth-child(2) {
                        text-align: left;
                    }
                </style>
            </head>
        
            <body>
                <div class="invoice-box">
                    <table cellpadding="0" cellspacing="0">
                        <tr class="top">
                            <td colspan="4">
                                <table>
                                    <tr>
                                        <td class="title">
                                            <img src="'.$logo.'" style="width: 100%; max-width: 300px" />
                                        </td>
        
                                        <td>
                                            Invoice #:'.$invoice->id.'<br />
                                             '.$invoice->created_at->format("d-m-Y").'<br />
                                            Due: '.$invoice->created_at->format("d-m-Y").'
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
        
                        <tr class="information">
                            <td colspan="4">
                                <table>
                                    <tr>
                                        <td>
                                            '.$setting->business_name.',<br />
                                            '.$setting->business_address.', <br />
                                        </td>
        
                                        <td>
                                            Bill To <br />
                                            <b>'.$customer->name.'</b><br />
                                            '.$customer->phone.'<br />
                                            
                                             
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0">
                        <tr class="heading">
                            <td>Item</td>
                            <td>Unit Price</td>
                            <td>Qty</td>
                            <td>Amount</td>
                        </tr>';
                            if($order->order_type == "tailoring"){
                                foreach ($order->outfits as $key => $outfit) {
                                
                                    $invoiceString .= '<tr class="item last">
                                    <td>'.$outfit->name.'</td>
                                    <td>'.number_format($outfit->price,2,".",",").'</td>
                                    <td>'.$outfit->qty.'</td>
                                    <td>'.number_format($outfit->getTotalAmount(),2,".",",").'</td>
                                    </tr>';
                                }
                                
                            }
                            if($order->order_type == "sales"){
                                //get all order items
                                foreach ($order->order_items as $key => $order_item) {
                                    $item = Item::find($order_item->item_id);
                                    $invoiceString .= '<tr class="item last">
                                    <td>'.$item->description.'</td>

                                    <td>'.number_format($item->price,2,".",",").'</td>
                                </tr>';

                                }
                                $invoiceString .= '

                            <tr class="total">
                                <td></td>

                                <td>Total: '.number_format($order->total_amount,2,".",",").'</td>
                            </tr>';
                            }
                            $invoiceString .= '</table>';
                            $invoiceString .= '<table>';
                            $invoiceString .= '<tr class=""><td colspan=""></td>
                            <td>Sub Total: '.number_format($order->total_amount,2,".",",").'</td></tr>';
                            $invoiceString .= '<tr class=""><td colspan=""></td>
                            <td>VAT: '.number_format($order->vat,2,".",",").'</td></tr>';
                            $invoiceString .= '<tr class="total"><td colspan=""></td>
                            <td>Grand Total: '.formatCurrency($order->getTotalAmountPlusVAT()).'</td></tr>
                    </table>';
                            $invoiceString .='<table><tr class="information">
                            <td colspan="4">
                                <table>
                                    <tr>
                                        <td>
                                            <b>Payment Instructions</b><br />
                                            '.$setting->business_payment_advice.'
                                        </td>

                                        <td>
                                            
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <img src="'.$payment_qr.'" style="width: 100%; max-width: 150px" />
                                        </td>

                                        <td>
                                            
                                            
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr></table>';
                            $invoiceString .= '</div>
                                </body>
                            </html>';
        $pdf->loadHTML($invoiceString);
        return $pdf->stream();
    }

    function printReceipt($invoice){
        $pdf = FacadesApp::make('dompdf.wrapper');
        $setting = Setting::where('user_id', auth()->user()->user_account_id)->first();
        $image = $setting->business_logo ?? 'handyseam_logo.png';
        $logo = asset('/storage/logo_images/'.$image);
        $payment_qr = '';
        if($setting->payment_qr){
            $payment_qr = asset('/storage/logo_images/'.$setting->payment_qr);
        }
        $invoiceString = '<!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8" />
                <title>Invoice For Customer</title>
        
                <style>
                    .invoice-box {
                        max-width: 800px;
                        margin: auto;
                        padding: 30px;
                        border: 1px solid #eee;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
                        font-size: 16px;
                        line-height: 24px;
                        font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;
                        color: #555;
                    }
        
                    .invoice-box table {
                        width: 100%;
                        line-height: inherit;
                        text-align: left;
                    }
        
                    .invoice-box table td {
                        padding: 5px;
                        vertical-align: top;
                    }
        
                    .invoice-box table tr td:nth-child(2) {
                        text-align: right;
                    }
                    .invoice-box table tr td:nth-child(3) {
                        text-align: right;
                    }
                    .invoice-box table tr td:nth-child(4) {
                        text-align: right;
                    }
        
                    .invoice-box table tr.top table td {
                        padding-bottom: 20px;
                    }
        
                    .invoice-box table tr.top table td.title {
                        font-size: 45px;
                        line-height: 45px;
                        color: #333;
                    }
        
                    .invoice-box table tr.information table td {
                        padding-bottom: 40px;
                    }
        
                    .invoice-box table tr.heading td {
                        background: #eee;
                        border-bottom: 1px solid #ddd;
                        font-weight: bold;
                    }
        
                    .invoice-box table tr.details td {
                        padding-bottom: 20px;
                    }
        
                    .invoice-box table tr.item td {
                        border-bottom: 1px solid #eee;
                    }
        
                    .invoice-box table tr.item.last td {
                        border-bottom: none;
                    }
        
                    .invoice-box table tr.total td:nth-child(2) {
                        border-top: 2px solid #eee;
                        font-weight: bold;
                    }
        
                    @media only screen and (max-width: 600px) {
                        .invoice-box table tr.top table td {
                            width: 100%;
                            display: block;
                            text-align: center;
                        }
        
                        .invoice-box table tr.information table td {
                            width: 100%;
                            display: block;
                            text-align: center;
                        }
                    }
        
                    /** RTL **/
                    .invoice-box.rtl {
                        direction: rtl;
                        font-family: Tahoma, \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;
                    }
        
                    .invoice-box.rtl table {
                        text-align: right;
                    }
        
                    .invoice-box.rtl table tr td:nth-child(2) {
                        text-align: left;
                    }
                </style>
            </head>
        
            <body>
                <div class="invoice-box">
                    <table cellpadding="0" cellspacing="0">
                        <tr class="top">
                            <td colspan="4">
                                <table>
                                    <tr>
                                        <td class="title">
                                            <img src="'.$logo.'" style="width: 100%; max-width: 300px" />
                                        </td>
        
                                        <td>
                                            Invoice #:'.$invoice->id.'<br />
                                             '.$invoice->created_at->format("d-m-Y").'<br />
                                            Due: '.$invoice->created_at->format("d-m-Y").'
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
        
                        <tr class="information">
                            <td colspan="4">
                                <table>
                                    <tr>
                                        <td>
                                            '.$setting->business_name.',<br />
                                            '.$setting->business_address.', <br />
                                        </td>
        
                                        <td>
                                            Bill To <br />
                                            <b>'.$invoice->order->customer->name.'</b><br />
                                            '.$invoice->order->customer->phone.'<br />
                                            
                                             
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0">
                        <tr class="heading">
                            <td>Item</td>
                            <td>Unit Price</td>
                            <td>Qty</td>
                            <td>Amount</td>
                        </tr>';
                            if($invoice->order->order_type == "tailoring"){
                                foreach ($invoice->order->outfits as $key => $outfit) {
                                
                                    $invoiceString .= '<tr class="item last">
                                    <td>'.$outfit->name.'</td>
                                    <td>'.number_format($outfit->price,2,".",",").'</td>
                                    <td>'.$outfit->qty.'</td>
                                    <td>'.number_format($outfit->getTotalAmount(),2,".",",").'</td>
                                    </tr>';
                                }
                                
                            }
                            if($invoice->order->order_type == "sales"){
                                //get all order items
                                foreach ($invoice->order->order_items as $key => $order_item) {
                                    $item = Item::find($order_item->item_id);
                                    $invoiceString .= '<tr class="item last">
                                    <td>'.$item->description.'</td>

                                    <td>'.number_format($item->price,2,".",",").'</td>
                                </tr>';

                                }
                                $invoiceString .= '

                            <tr class="total">
                                <td></td>

                                <td>Total: '.number_format($invoice->order->total_amount,2,".",",").'</td>
                            </tr>';
                            }
                            $invoiceString .= '</table>';
                            $invoiceString .= '<table>';
                            $invoiceString .= '<tr class=""><td colspan=""></td>
                            <td>Sub Total: '.number_format($invoice->order->total_amount,2,".",",").'</td></tr>';
                            $invoiceString .= '<tr class=""><td colspan=""></td>
                            <td>VAT: '.number_format($invoice->order->vat,2,".",",").'</td></tr>';
                            $invoiceString .= '<tr class="total"><td colspan=""></td>
                            <td>Grand Total: '.formatCurrency($invoice->order->getTotalAmountPlusVAT()).'</td></tr>
                    </table>';
                            $invoiceString .='<p><b>Payments</b></p>
                            <table>
                            
                                    <tr>
                                        <td><b>Date</b></td>
                                        <td><b>Amount</b></td>
                                    </tr>';
                                    foreach ($invoice->payments as $key => $payment) {
                                        $invoiceString .= '<tr><td>'.$payment->created_at->format("d-m-Y").'</td><td>'.formatCurrency($payment->amount).'</td></tr>';
                                    }
                            $invoiceString .= '<tr>
                            <td><b>Outstanding Balance:' .formatCurrency($invoice->getOutstandingBalance()).'</b></td>
                        </tr></table>
                        ';
                        $invoiceString .='<table><tr class="information">
                            <td colspan="4">
                                <table>
                                    <tr>
                                        <td>
                                            <b>Payment Instructions</b><br />
                                            '.$setting->business_payment_advice.'
                                        </td>

                                        <td>
                                            
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <img src="'.$payment_qr.'" style="width: 100%; max-width: 150px" />
                                        </td>

                                        <td>
                                            
                                            
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr></table>
                        
                        </div>
                                </body>
                            </html>';
        $pdf->loadHTML($invoiceString);
        return $pdf->stream();
    }

}
