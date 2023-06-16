<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Auth;

class ItemsController extends Controller
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
        $items = Item::getAll();
        $view = Auth::user()->user_type . '.items.index';
        return view($view)->with('items',$items);
    }
    public function filter($filter_by, $filter_value)
    {
        //get a list of all businesses belonging to the user
        $shops = Shop::all();
        return view('admin.shops.index')->with('shops',$shops);
    }
    public function search(Request $request)
    {
        $view = Auth::user()->user_type;
        if($request->search_by == 'desc'){
            $items = Item::where('description','like', '%'."{$request->search_value}".'%')->
            where('user_id','=', Auth::user()->id)->paginate(10);
            if($request->origin == 'order_creation'){
                return view($view.'.orders.create.sales.step2')->with('items', $items);
            }
            return view($view.'.items.index')->with('items', $items);
            
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $view = Auth::user()->user_type ;
        return view($view.'.items.create');
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
            'price' => ['required'],
            'qty' => ['required'],
        ]);
        
        $item = new Item;
        $item->description = $request->description;
        $item->category = $request->category_id;
        $item->inventory_quantity = $request->qty;
        $item->price = $request->price;
        $item->cost_price = $request->cost_price;
        $item->user_id = Auth::user()->id;

        $item->save();
        
        return redirect('items')->with('status', 'Item was added successfully');
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
        $view = Auth::user()->user_type ;
        return view($view.'.items.show')->with('item',$item);
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
            'price' => ['required'],
            'qty' => ['required'],
            'category_id' => ['required'],
        ]);
        
        $item = Item::find($id);
        $item->description = $request->description;
        $item->category = $request->category_id;
        $item->inventory_quantity = $request->qty;
        $item->cost_price = $request->cost_price;
        $item->price = $request->price;

        $item->save();
        
        return redirect('items')->with('status', 'Item was updated successfully');
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
