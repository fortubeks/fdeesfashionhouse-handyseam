<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
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
        $purchases = auth()->user()->user_account->purchases()->paginate(10);
        return view('pages.purchases.index')->with('purchases',$purchases);
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.purchases.create');
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
            
        ]);
        
        $purchase = new Purchase();
        $purchase->item_id = $request->item_id;
        $purchase->unit_cost = $request->unit_cost;
        $purchase->qty = $request->qty;
        $purchase->notes = $request->notes;
        $purchase->amount = $request->amount;
        $purchase->user_id = auth()->user()->user_account_id;
       
        $purchase->save();

        $purchase->created_at = $request->created_at;
        $purchase->save();

        //update or increase item qty
        $item = Item::find($purchase->item_id);
        $item->cost_price = $request->unit_cost;
        $item->inventory_quantity += $purchase->qty;
        $item->save();
        
        return redirect('items')->with('status', 'Purchase was added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchase = Purchase::findOrFail($id);
        return view('pages.purchases.show')->with('purchase',$purchase);
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
        
        $purchase = Purchase::find($id);
        $item = Item::find($request->item_id);

        if($request->qty > $purchase->qty){
            $item->inventory_quantity += $request->qty;
        }
        if($request->qty < $purchase->qty){
            $item->inventory_quantity -= $request->qty;
        }
        $item->save();

        $purchase->item_id = $request->item_id;
        $purchase->unit_cost = $request->unit_cost;
        $purchase->qty = $request->qty;
        $purchase->notes = $request->notes;
        $purchase->amount = $request->amount;
        $purchase->save();

        return redirect('purchases')->with('status', 'Purchase was updated successfully');
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
