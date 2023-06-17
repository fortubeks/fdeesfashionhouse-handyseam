<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemCategory;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get a list of all items in inventory
        $items = Item::getAll();
        return response()->json($items, 200);
    }
    
    public function search(Request $request)
    {
        if($request->search_by == 'desc'){
            $items = Item::where('description','like', '%'."{$request->search_value}".'%')->
            where('user_id','=', auth()->user()->user_account_id)->get();
            
            return response()->json($items, 201);
            
        }
        
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
            'category_id' => ['required'],
            'description' => ['required'],
            'qty' => ['required'],
        ]);
        
        $item = new Item;
        $item->description = $request->description;
        $item->item_category_id = $request->category_id;
        $item->inventory_quantity = $request->qty;
        $item->unit_measurement = $request->unit_measurement;
        $item->for_sale = isset($request->for_sale) ? 1 : 0;
        $item->user_id = auth()->user()->user_account_id;

        $item->save();
        
        return response()->json($item, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::findOrFail($id);
        return response()->json($item, 200);
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
            'price' => ['required'],
            'qty' => ['required'],
            'cost_price' => ['required'],
            'category_id' => ['required'],
        ]);
        
        $item = Item::find($id);
        $item->description = $request->description;
        $item->item_category_id = $request->category_id;
        $item->inventory_quantity = $request->qty;
        //$item->cost_price = $request->cost_price;
        $item->price = $request->price;
        $item->for_sale = isset($request->for_sale) ? 1 : 0;

        $item->save();
        
        return response()->json($item, 200);
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
