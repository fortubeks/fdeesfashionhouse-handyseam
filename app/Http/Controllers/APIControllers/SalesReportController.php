<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Http\Request;
use App\Models\Order;

class SalesReportController extends Controller
{

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
        return response()->json($orders,200);
    }
}