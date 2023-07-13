<?php

namespace App\Http\Controllers;

use App\Models\Outfit;
use Illuminate\Http\Request;

class OutfitController extends Controller
{
    public function index()
    {
        //get a list of all items in inventory
        $outfits = auth()->user()->user_account->outfits;
        return view('pages.outfits.index')->with('outfits',$outfits);
    }
    
    public function search(Request $request)
    {
        if($request->search_by == 'desc'){
            $outfits = Outfit::where('name','like', '%'."{$request->search_value}".'%')->
            where('user_id','=', auth()->user()->user_account_id)->paginate(10);
            
            return view('pages.outfits.index')->with('outfits', $outfits);
            
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.outfits.create');
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
            'name' => ['required'],
        ]);

        $m_details = array();
        foreach($request->measurement_details as $key=>$measurement_detail)
        {
            //slug each detail
            $slugged_detail = $this->slugify($measurement_detail);
            $m_details[$slugged_detail] = $measurement_detail;
        }
        $encoded_M_details = json_encode($m_details);
        
       Outfit::create([
        'name' => $request->name,
        'price' => $request->price,
        'parent_id' => $request->parent_id,
        'measurement_details' => $encoded_M_details,
       ]);
        
        return redirect('outfits')->with('status', 'Outfit was added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $outfit = Outfit::find($id);
        return view('pages.outfits.show', compact('outfit'));
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
        $validatedData = $request->validate([
            'name' => ['required'],
        ]);

        $m_details = array();
        foreach($request->measurement_details as $key=>$measurement_detail)
        {
            //slug each detail
            $slugged_detail = $this->slugify($measurement_detail);
            $m_details[$slugged_detail] = $measurement_detail;
        }
        $encoded_M_details = json_encode($m_details);
        
        $outfit = Outfit::find($id);
       $outfit->update([
        'name' => $request->name,
        'price' => $request->price,
        'parent_id' => $request->parent_id,
        'measurement_details' => $encoded_M_details,
       ]);
        
        return redirect('outfits')->with('status', 'Outfit was updated successfully');
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
