<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Expense;
use Carbon\Carbon;

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
        $_orders = auth()->user()->user_account->orders();
        $orders = $_orders->paginate(10);
        $orders_sum = $_orders->sum('total_amount');
        $orders_count = $_orders->count();

        $_expenses = auth()->user()->user_account->expenses();
        $expenses = $_expenses->paginate(10);
        $expenses_sum = $_expenses->sum('amount');
        $profit = $orders_sum - $expenses_sum;
        
        return view('pages.sales-report.index')->with(compact('orders','expenses','orders_sum',
        'expenses_sum','profit','orders_count'));
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
        
        if(isset($request->date_range)){
            switch ($request->date_range) {
                case "this":
                    $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
                    $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
                    break;
        
                case "last":
                    $start_date = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                    $end_date = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                    break;
        
                case "last-3":
                    $start_date = Carbon::now()->subMonths(2)->startOfMonth()->format('Y-m-d');
                    $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
                    break;
        
                case "last-6":
                    $start_date = Carbon::now()->subMonths(5)->startOfMonth()->format('Y-m-d');
                    $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
                    break;
        
                case "this-y":
                    $start_date = Carbon::now()->startOfYear()->format('Y-m-d');
                    $end_date = Carbon::now()->endOfYear()->format('Y-m-d');
                    break;
        
                case "all-time":
                    // You might set a reasonable "all-time" range, e.g., the past 5 years
                    $startDate = Carbon::now()->subYears(5)->format('Y-m-d');
                    $end_date = Carbon::now()->format('Y-m-d');
                    break;
        
                // Add more cases for other options as needed
        
                default:
                    // Handle the default case or set default date range
                    break;
            }
        }
        //dd($startDate);

        $_orders = Order::whereBetween('created_at',[$start_date,$end_date])->get();
        $_expenses = Expense::whereBetween('created_at',[$start_date,$end_date])->get();
        $revenue = $_orders->sum('total_amount');
        $orders_count = $_orders->count();
        $cost = $_expenses->sum('amount');
        $profit = $revenue - $cost;

        $orders_query = Order::where('created_at','>=', "{$start_date}")
        ->where('created_at', '<=', "{$end_date}");
        $orders_sum = $orders_query->sum('total_amount');
        $orders = $orders_query->paginate(10)->appends([
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
                ]);
        $expenses_query = Expense::where('value_date','>=', "{$start_date}")
        ->where('value_date', '<=', "{$end_date}");
        $expenses_sum = $expenses_query->sum('amount');
        $expenses = $expenses_query->paginate(10)->appends([
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
                'expenses_sum' => $expenses_sum,
                'orders_sum' => $orders_sum,
                ]);
        return view('pages.sales-report.index')->with(compact('orders','expenses','start_date','end_date',
        'orders_sum','expenses_sum','revenue','orders_count','profit'));
    }
}