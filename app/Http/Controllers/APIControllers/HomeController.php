<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\OrdersController;
use App\Models\AppSetting;
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
        
        $ordersController = new OrdersController();
        $orders = $ordersController->getOrdersDueThisWeek();
        if(auth()->user()->user_type == "admin"){
            //$settings = AppSetting::where('user_id',Auth::user()->id);
            if (!AppSetting::where('user_id', '=', auth()->user()->id)->exists()) {
                // setings not found
                return view('admin.settings.setup.measurements');
              }
            
            return view('dashboard')->with('orders', $orders);
        }
        //else as user is staff
        if(auth()->user()->user_type == "staff"){
            return view('staff.dashboard')->with('orders', $orders);
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
