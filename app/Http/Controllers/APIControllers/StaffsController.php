<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffsController extends Controller
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
        //get a list of all businesses belonging to the user
        $staffs = Staff::getAll();
        return view('admin.staffs.index')->with('staffs',$staffs);
    }
    public function filter($filter_by, $filter_value)
    {
        //get a list of all businesses belonging to the user
        $shops = Shop::all();
        return view('admin.shops.index')->with('shops',$shops);
    }
    public function search(Request $request)
    {
        if($request->search_by == 'name'){
            $staffs = FieldOfficer::where('first_name','LIKE',"{$request->name}%")->get();
            if(count($staffs)>0){
                return view('admin.staffs.index')->with('staffs', $staffs);
            }
            else{
                return redirect('staffs')->withFail('Staff not found');
            }
            
        }
    }
    public function searchAjax(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required'],
        ]);
        $result = DB::table('staffs')
            ->where('first_name', 'like', $request->name. '%')
            ->get(); 
            if(count($result)>0){
                return response()->json(array('staffs'=> $result), 200);
                //return "OK";
            }
            else{
                return response()->json(array('staffs'=> ""), 200);
                //return "Not Found";
            }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.staffs.create');
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
            'firstname' => ['required'],
            'lastname' => ['required'],
            'phone' => ['required'],
        ]);

        $staff = new Staff;
          
        $staff->first_name = $request->firstname;
        $staff->last_name = $request->lastname;
        $staff->phone = $request->phone;
        $staff->owner_id = Auth::user()->id;

        $staff->save();

        $user = new User();
        $user->name = $staff->first_name . ' '. $staff->last_name;
        $user->user_type = 'staff';
        $user->password = Hash::make($staff->phone);
        $user->email = 'staff'.$staff->id.'@tailorapp.com';
        $user->save();

        $staff->user_id = $user->id;
        $staff->save();
        
        return redirect('staffs')->with('status', 'Staff was added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $staff = Staff::findOrFail($id);
        return view('admin.staffs.show')->with('staff',$staff);
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
            'firstname' => ['required'],
            'lastname' => ['required'],
            'phone' => ['required'],
        ]);

        $staff = Staff::find($id);
        
        $staff->first_name = $request->firstname;
        $staff->last_name = $request->lastname;
        $staff->phone = $request->phone;

        $staff->save();

        $user = User::find($staff->user_id);
        $user->user_status = ($request->user_status == 1) ? 1 : 0;
        $user->save();
        
        return redirect('staffs')->with('status', 'Staff was updated successfully');
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
