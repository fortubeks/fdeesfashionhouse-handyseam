<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Item;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class CustomersController extends Controller
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
        $customers = Customer::orderBy('name','desc')->
        where('user_id','=', auth()->user()->user_account_id)
        ->where('is_deleted',0)->paginate(10);
        $view = 'pages.customers.index';
        return view($view)->with('customers',$customers);
    }
    
    public function search(Request $request)
    {
        $view = 'pages';
        if($request->search_by == 'phone'){
            $customers = Customer::where('phone',"{$request->search_value}")
            ->where('user_id','=', auth()->user()->user_account_id)
            ->where('is_deleted',0)
            ->paginate(20)->appends([
                'search_value' => request('search_value'),
                'search_by' => request('search_by'),
                'origin' => request('origin'),
                'order_type' => request('order_type'),
                ]);
            if(count($customers)>0){
                if($request->origin == 'order_creation'){
                    return view($view.'.orders.create.'.$request->order_type.'.step1')->with('customers', $customers);
                }
                return view($view.'.customers.index')->with('customers', $customers);
            }
            else{
                if($request->origin == 'order_creation'){
                    $status = 'Customer not found. Check the details inputed or create new customer to proceed with order creation';
                    return view($view.'.orders.create.'.$request->order_type.'.step1')->with(compact('status','customers'));
                }
                return redirect('customers')->withFail('Customer not found');
            }
            
        }
        if($request->search_by == 'name'){
            $customers = Customer::where('name', 'like', '%'."{$request->search_value}".'%')
            ->where('user_id','=', auth()->user()->user_account_id)
            ->where('is_deleted',0)
            ->paginate(20)->appends([
                'search_value' => request('search_value'),
                'search_by' => request('search_by'),
                'origin' => request('origin'),
                'order_type' => request('order_type'),
                ]);
            if(count($customers)>0){
                if($request->origin == 'order_creation'){
                    return view('pages.orders.create.'.$request->order_type.'.step1')->with('customers', $customers);
                }
                return view($view.'.customers.index')->with('customers', $customers);
            }
            else{
                if($request->origin == 'order_creation'){
                    $status = 'Customer not found. Check the details inputed or create new customer to proceed with order creation';
                    return view($view.'.orders.create.'.$request->order_type.'.step1')->with(compact('status','customers'));
                }
                return redirect('customers')->withFail('Customer not found');
            }
            
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('pages.customers.create');
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
            'phone' => ['required'],
            'name' => ['required'],
        ]);
        if(Customer::where('user_id',auth()->user()->user_account->id)->where('phone',$request->phone)->exists()){
            return redirect('customers/')->with('error', 'Phone number already taken');
        }
        
        $customer = new Customer;
        $customer->user_id = auth()->user()->user_account_id;
        $customer->phone = $request->phone;
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->email = $request->email;
    
        $customer->save();

        if($request->origin == 'order_creation'){
            session(['customer_id' => $customer->id]);
            if($request->order_type == 'sales'){
                $items = Item::getAll();
                return view('pages.orders.create.'.$request->order_type.'.step2', compact('items', 'customer'))->with('status', 'Customer was added successfully. Create Order');
            }
            return view('pages.orders.create.'.$request->order_type.'.step2', compact('customer'))->with('status', 'Customer was added successfully. Create Order');
        }
        
        return redirect('customers/'.$customer->id)->with('status', 'Customer was added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);  
        $orders = $customer->orders()->paginate(10);
        return view('pages.customers.show')->with(compact('customer','orders'));
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
        $customer->email = $request->email;
    
        $customer->save();
        
        return redirect('customers/'.$customer->id)->with('status', 'Customer was updated successfully');
    }

    public function updateMeasurement(Request $request, $id)
    {
        $data = $request->except('_token','origin','customer_id');
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
            $view = 'pages.orders.create.tailoring.step3';
            return view($view);
        }
        return redirect('customers/'.$customer->id)->with('status', 'Customer measurement was updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect('customers')->with('status','Delete successful');
    }

    public function softDeleteCustomer($customer_id){
        $customer = Customer::find($customer_id);
        if($customer){
            $customer->is_deleted = 1;
            $customer->save();
            return redirect('customers')->with('status','Delete successful');
        }
        return redirect('customers')->with('status','Customer not found');
    }

    public function resendVerificationEmail(){
        $users = User::where('email_verified_at', null)->get();
        foreach($users as $user){
            if (filter_var($user->email, FILTER_VALIDATE_EMAIL) && $user->id > 507) {
                $user->sendEmailVerificationNotification();
              }
        }
    }

    public function export(){
        return Excel::download(new CustomersExport(), 'customers.xlsx');
    }
}
