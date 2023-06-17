<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get a list of all businesses belonging to the user
        $staffs = Staff::getAll();
        return response()->json($staffs,200);
    }

    public function search(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required'],
        ]);
        $staffs = Staff::where('first_name','LIKE',"{$request->name}%")->get();
        return response()->json($staffs,200);
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
        $staff->owner_id = auth()->user()->id;

        $staff->save();

        $user = new User();
        $user->name = $staff->first_name . ' '. $staff->last_name;
        $user->user_type = 'staff';
        $user->password = Hash::make($staff->phone);
        $user->email = 'staff'.$staff->id.'@tailorapp.com';
        $user->save();

        $staff->user_id = $user->id;
        $staff->save();
        
        return response()->json($staff,200);
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
        return response()->json($staff,200);
    }

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
        
        return response()->json($staff,200);
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
