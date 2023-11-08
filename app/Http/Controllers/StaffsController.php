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
        //$this->authorizeResource(User::class);
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
        return view('pages.staffs.index')->with('staffs',$staffs);
    }
    
    public function search(Request $request)
    {
        if($request->search_by == 'name'){
            $staffs = Staff::where('first_name','LIKE',"{$request->name}%")->get();
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
        return view('pages.staffs.create');
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
            'address' => ['required'],
            'phone' => ['required','unique:staffs'],
        ]);
 
        $user = null;
        if(isset($request->email) && isset($request->email)){
            $validatedData = $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ]);
            $user = new User();
            $user->name = $request->firstname . ' '. $request->lastname;
            $user->phone = $request->phone;
            $user->user_type = $request->role;
            $user->password = Hash::make($request->password);
            $user->email = $request->email;
            $user->user_account_id = auth()->user()->id;
            $user->save();
        }
        

        $staff = new Staff;
        $staff->first_name = $request->firstname;
        $staff->last_name = $request->lastname;
        $staff->phone = $request->phone;
        $staff->role = $request->role;
        $staff->address = $request->address;
        $staff->salary_amount = $request->salary_amount;
        $staff->account_details = $request->account_details;
        $staff->other_information = $request->other_information;
        $staff->user_account_id = auth()->user()->id;
        if($user){
            $staff->user_id = $user->id;
        }
       
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
        $this->authorize(auth()->user(),  $staff);
        return view('pages.staffs.show')->with('staff',$staff);
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
            'address' => ['required'],
            'phone' => ['required'],
        ]);

        $staff = Staff::find($id);
        
        $staff->first_name = $request->firstname;
        $staff->last_name = $request->lastname;
        $staff->phone = $request->phone;
        $staff->role = $request->role;
        $staff->address = $request->address;
        $staff->salary_amount = $request->salary_amount;
        $staff->account_details = $request->account_details;
        $staff->other_information = $request->other_information;
       
        $staff->save();

        if($staff->user){
            $user = User::find($staff->user_id);
            $user->user_status = ($request->user_status == 1) ? 1 : 0;
            if(isset($request->password)){
                $user->password = Hash::make($request->password);
            }
            $user->save();
        }
        
        
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
