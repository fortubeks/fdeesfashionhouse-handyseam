<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\OutfitsOrders;
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
        foreach(auth()->user()->user_account->orders()->orderBy('created_at','desc')->get() as $order){
            foreach($order->outfits as $outfit){
                if($outfit->tailor){
                    $record = (object)[];
                    $record->outfit_id = $outfit->id;
                    $record->tailor = $outfit->tailor->getFullName();
                    $record->customer = $outfit->order->customer->name;
                    $record->style = $outfit->name;
                    $record->fitting_date = $outfit->order->expected_delivery_date;
                    $record->status = $outfit->order->status;
                    $record->amount = $outfit->tailor_cost;
                    $record->payment_date = $outfit->payment_date;
                    array_push($tailor_payments_by_outfits_made_weekly, $record);
                }
            }
        }
        $data = paginate($tailor_payments_by_outfits_made_weekly,10);
        return view('pages.expenses.outfit-payments')->with('tailor_payments_by_outfits_made_weekly', $data);
    }

    public function updateTailorPaymentDate(Request $request){
        $outfit = OutfitsOrders::find($request->id);
        $outfit->payment_date = $request->value;
        $outfit->save();
        return $outfit;
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
        //dd($begining_date.'--'.$end_date);
        $orders = auth()->user()->user_account->orders()->whereBetween('expected_delivery_date',[$begining_date,$end_date])
        ->orderBy('created_at','desc')->get();
        
        $tailor_payments_by_outfits_made_weekly = [];
        foreach($orders as $order){
            foreach($order->outfits as $outfit){
                if($outfit->tailor){
                    $record = (object)[];
                    $record->outfit_id = $outfit->id;
                    $record->tailor = $outfit->tailor->getFullName();
                    $record->customer = $outfit->order->customer->name;
                    $record->style = $outfit->name;
                    $record->fitting_date = $outfit->order->expected_delivery_date;
                    $record->status = $outfit->order->status;
                    $record->amount = $outfit->tailor_cost;
                    $record->payment_date = $outfit->payment_date;
                    array_push($tailor_payments_by_outfits_made_weekly, $record);
                }
            }
        }
       
        return view('pages.expenses.outfit-payments')->with('tailor_payments_by_outfits_made_weekly', $tailor_payments_by_outfits_made_weekly);
    }

    public function getCustomerOrders(Request $request)
    {
        //validate customer entry, store and set view to list of customers
        $validatedData = $request->validate([
            'name' => ['required'],
        ]);
        
        //return all the orders by the customer
        // $orders = auth()->user()->user_account->orders()->whereBetween('expected_delivery_date',[$begining_date,$end_date])
        // ->orderBy('created_at','desc')->get();

        $customers = Customer::where('name', 'like', '%'."{$request->name}".'%')
            ->where('user_id','=', auth()->user()->user_account_id)
            ->get();
        $orders = [];
        foreach ($customers as $customer){
            if($customer->orders->count() > 0){
                foreach($customer->orders as $order){
                    $orders[] = $order;
                }
            }
        }
        //dd($orders);
        $tailor_payments_by_outfits_made_weekly = [];
        foreach($orders as $order){
            foreach($order->outfits as $outfit){
                $record = (object)[];
                $record->outfit_id = $outfit->id;
                $record->tailor = $outfit->tailor ? $outfit->tailor->getFullName() : "No tailor assigned";
                $record->customer = $outfit->order->customer->name;
                $record->style = $outfit->name;
                $record->fitting_date = $outfit->order->expected_delivery_date;
                $record->status = $outfit->order->status;
                $record->amount = $outfit->tailor_cost;
                $record->payment_date = $outfit->payment_date;
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