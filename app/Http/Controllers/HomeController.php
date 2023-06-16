<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //check for setup
        $orders = new Order;
        $orders_due = $orders->getOrdersDueThisWeek();
        $orders_recent = $orders->getRecentOrders();
        if(auth()->user()->user_type == "admin"){
            //$settings = AppSetting::where('user_id',Auth::user()->id);
            if (auth()->user()->app_settings->business_name == '') {  
                return view('pages.settings.setup.business-info')->with('setting',auth()->user()->app_settings);
              }
            
            return view('dashboard', compact('orders_due','orders_recent'));
        }
        //else as user is staff
        if(auth()->user()->user_type == "manager"){
            return view('dashboard', compact('orders_due','orders_recent'));
        }
    }

    public function showChangePasswordForm(){
        return view('auth.passwords.change');
    }


    public function changePassword(Request $request){

        if (!(Hash::check($request->get('current-password'), auth()->user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = auth()->user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success","Password changed successfully !");

    }
}
