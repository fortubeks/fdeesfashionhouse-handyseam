<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Auth;

class SalesReportController extends Controller
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
        $view = Auth::user()->user_type . '.sales-report.index';
        return view($view)->with('orders',[]);
    }

    public function showReport(Request $request){
        $validatedData = $request->validate([
            'start_date' => ['required'],
            'end_date' => ['required'],
        ]);
        //get all orders within a time range
        //get all invoices on those orders
        //get all payments made on those invoices
        $orders = Order::where('created_at','>=', "{$request->start_date}")
        ->where('created_at', '<=', "{$request->end_date}")->paginate(10)->appends([
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
                ]);
        $view = Auth::user()->user_type . '.sales-report.index';
        return view($view)->with('orders', $orders);
    }
}