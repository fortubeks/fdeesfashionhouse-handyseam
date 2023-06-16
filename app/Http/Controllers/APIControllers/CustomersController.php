<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::orderBy('name','desc')->get();
        return response()->json($customers);
    }
    
    public function search(Request $request)
    {
        if($request->search_by == 'phone'){
            $customers = Customer::where('phone',"{$request->search_value}")
            ->get();
            return $customers;
            
        }
        if($request->search_by == 'name'){
            $customers = Customer::where('name', 'like', '%'."{$request->search_value}".'%')->get();
            return $customers;
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'phone' => ['required', 'unique:customers'],
            'name' => ['required'],
        ]);
        
        $customer = new Customer;
        $customer->user_id = auth()->user()->id;
        $customer->phone = $request->phone;
        $customer->name = $request->name;
        $customer->address = $request->address;
    
        $customer->save();
        return $customer;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::find($id);
        return $customer;
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
            'name' => ['required'],
        ]);
        
        $customer = Customer::find($id);
        $customer->phone = $request->phone;
        $customer->name = $request->name;
        $customer->address = $request->address;
    
        $customer->save();
        
        return $customer;
    }

    public function updateMeasurement(Request $request, $id)
    {
        $data = $request->except('_token');
        $m_details = array();
        foreach($data as $element_name=>$measurement_value)
        {
            
            $m_details[$element_name] = $measurement_value;
            
        }
        $encoded_M_details = json_encode($m_details);
        
        $customer = Customer::find($id);
        
        $customer->measurement_details = $encoded_M_details;
        
        $customer->save();
        if($request->origin == 'order_creation'){
            $view = auth()->user()->user_type . '.orders.create.tailoring.step3';
            return view($view);
        }
        return redirect()->back()->with('status', 'Customer measurement was updated successfully');
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
