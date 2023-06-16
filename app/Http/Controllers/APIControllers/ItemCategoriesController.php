<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemCategory;
use Auth;

class ItemCategoriesController extends Controller
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
        $item_categories = ItemCategory::getAll();
        $view = Auth::user()->user_type . '.item-categories.index';
        return view($view)->with('item_categories',$item_categories);
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $view = Auth::user()->user_type ;
        return view($view.'.item-categories.create');
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
        
        $item_category = new ItemCategory;
        $item_category->name = $request->description;
        $item_category->user_id = Auth::user()->id;
       
        $item_category->save();
        
        return redirect('item-categories')->with('status', 'Category was added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item_category = ItemCategory::findOrFail($id);
        $view = Auth::user()->user_type ;
        return view($view.'.item-categories.show')->with('item_category',$item_category);
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
        
        $item_category = ItemCategory::find($id);
        $item_category->name = $request->description;
        $item_category->save();
        
        return redirect('item-categories')->with('status', 'Category was updated successfully');
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
