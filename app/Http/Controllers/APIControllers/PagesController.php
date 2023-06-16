<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Invoice;
use Auth;

class PagesController extends Controller
{
    //
    public function createtailoringorderstep1(){
        $view = 'pages.orders.create.tailoring.step1';
        return view($view);  
    }
    public function createtailoringorderstep2($customer_id){
        session(['customer_id' => $customer_id]);
        $customer = Customer::findOrFail($customer_id);
        
        $view = 'pages.orders.create.tailoring.step2';
        return view($view)->with('customer', $customer);
    }
    public function createtailoringorderstep3(){
        
        $view = 'pages.orders.create.tailoring.step3';
        return view($view);
    }
    public function createsalesorderstep1(){
        
        $view = 'pages.orders.create.sales.step1';
        return view($view);
    }
    public function createsalesorderstep2($customer_id){
        session(['customer_id' => $customer_id]);
        
        $items = Item::getAll();
        
        $view = 'pages.orders.create.sales.step2';
        return view($view)->with('items', $items);
    }
    public function createsalesorderstep3(){
        
        $view = 'pages.orders.create.sales.step3';
        return view($view);
    }
    public function createPaymentForInvoice(Request $request){
        $invoice = Invoice::find($request->invoice_id);
       
        $view = 'pages.payments.create';
        return view($view)->with('invoice', $invoice);
    }
    public function staffLogin()
    {
        return view('staff.login');
    }
    
}
