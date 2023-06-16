<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Order;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class ExpensesController extends Controller
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
        $expenses = Expense::getAll();
        $expenses_sum = $expenses->sum('amount');
        $expenses_count = $expenses->count();

        $view = 'pages.expenses.index';
        return view($view)->with(compact('expenses','expenses_sum','expenses_count'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expense_categories = ExpenseCategory::getAll();
        return view('pages.expenses.create')->with('expense_categories',$expense_categories);
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
            'description' => ['required'],
        ]);
        
        $expense = new Expense;
        $expense->description = $request->description;
        $expense->amount = $request->amount;
        $expense->value_date = $request->value_date;
        $expense->expense_category_id = $request->category_id;
        $expense->user_id = auth()->user()->id;
       
        $expense->save();
        
        return redirect('expenses')->with('status', 'Expense was added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expense = Expense::findOrFail($id);
        $expense_categories = ExpenseCategory::getAll();
        return view('pages.expenses.show', compact('expense', 'expense_categories'));
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
        //validate customer entry, store and set view to list of customers
        $validatedData = $request->validate([
            'description' => ['required'],
        ]);
        
        $expense = Expense::find($id);
        $expense->description = $request->description;
        $expense->amount = $request->amount;
        $expense->value_date = $request->value_date;
        $expense->expense_category_id = $request->category_id;
        $expense->save();
        
        return redirect('expenses')->with('status', 'Expense was updated successfully');
    }

    public function weeklyOutfitPaymentsIndex()
    {
        $tailor_payments_by_outfits_made_weekly = [];
        return view('pages.expenses.outfit-payments')->with('tailor_payments_by_outfits_made_weekly', $tailor_payments_by_outfits_made_weekly);
    }

    public function getWeeklyOutfitsPayments(Request $request)
    {
        //validate customer entry, store and set view to list of customers
        $validatedData = $request->validate([
            'week_ending' => ['required'],
        ]);
        $begining_date = date("Y-m-d", strtotime('-7days', strtotime($request->week_ending)));
        $end_date = date("Y-m-d", strtotime('-1days', strtotime($request->week_ending)));
        //return all the orders completed under that time frame

        $orders = Order::where('expected_delivery_date','>=', "{$begining_date}")
        ->where('expected_delivery_date', '<=', "{$end_date}")->get();
        
        $tailor_payments_by_outfits_made_weekly = [];
        foreach($orders as $order){
            foreach($order->outfits as $outfit){
                $record = (object)[];
                $record->tailor = $outfit->staff->getFullName();
                array_push($tailor_payments_by_outfits_made_weekly, $record);
            }
        }
       
        return view('pages.expenses.outfit-payments')->with('tailor_payments_by_outfits_made_weekly', $tailor_payments_by_outfits_made_weekly);
    }

    public function filter(Request $request){        
        $from = $request->from_filter;
        $to = $request->to_filter;
        $category_id = $request->category_id;
        $search_value = $request->search_value;

        $expenses_query = auth()->user()->user_account->expenses()->when($request->query('category_id'), fn(Builder $query, $category_id) => $query->where('expense_category_id', $category_id))
        ->when($request->query('search_value'), fn(Builder $query, $search_value) => $query->where('description','like', '%'."{$search_value}".'%'))
        ->where('value_date','>=',$from)->where('value_date','<=',$to);

        $expenses_sum = $expenses_query->sum('amount');
        $expenses_count = $expenses_query->count();

        $expenses = $expenses_query->paginate(2)->appends([
                'search_value' => request('search_value'),
                'category_id' => request('category_id'),
                'from' => request('from'),
                'to' => request('to'),
                'expenses_sum' => $expenses_sum,
                'expenses_count' => $expenses_count,
                ]);;

        return view('pages.expenses.index')->with(compact('expenses','from','to','category_id','search_value','expenses_sum','expenses_count'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}