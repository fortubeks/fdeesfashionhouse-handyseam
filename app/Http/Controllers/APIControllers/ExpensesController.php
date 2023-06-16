<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Auth;

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
        //get a list of all items in inventory
        $expenses = Expense::getAll();
        $view = Auth::user()->user_type . '.expenses.index';
        return view($view)->with('expenses',$expenses);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $view = Auth::user()->user_type ;
        return view($view.'.expenses.create');
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
        $expense->category_id = $request->category_id;
        $expense->user_id = Auth::user()->id;
       
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
        $view = Auth::user()->user_type ;
        return view($view.'.expenses.show')->with('expense',$expense);
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
        $expense->category_id = $request->category_id;
        $expense->save();
        
        return redirect('expenses')->with('status', 'Expense was updated successfully');
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