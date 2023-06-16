<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Expense;

class SalesReportController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = auth()->user()->user_account->orders()->paginate(10);
        $orders_sum = auth()->user()->user_account->orders()->sum('total_amount');
        $expenses = auth()->user()->user_account->expenses()->paginate(10);
        $expenses_sum = auth()->user()->user_account->expenses()->sum('amount');
        return view('pages.sales-report.index')->with(compact('orders','expenses','orders_sum','expenses_sum'));
    }

    public function showReport(Request $request){
        $validatedData = $request->validate([
            'start_date' => ['required'],
            'end_date' => ['required'],
        ]);
        //get all orders within a time range
        //get all invoices on those orders
        //get all payments made on those invoices
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $orders_query = Order::where('created_at','>=', "{$request->start_date}")
        ->where('created_at', '<=', "{$request->end_date}");
        $orders_sum = $orders_query->sum('total_amount');
        $orders = $orders_query->paginate(10)->appends([
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
                ]);
        $expenses_query = Expense::where('value_date','>=', "{$request->start_date}")
        ->where('value_date', '<=', "{$request->end_date}");
        $expenses_sum = $expenses_query->sum('amount');
        $expenses = $expenses_query->paginate(10)->appends([
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
                'expenses_sum' => $expenses_sum,
                'orders_sum' => $orders_sum,
                ]);
        return view('pages.sales-report.index')->with(compact('orders','expenses','start_date','end_date','orders_sum','expenses_sum'));
    }
}