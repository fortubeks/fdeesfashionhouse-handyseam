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

    function sendwhatsappmessage() {
        $to = "2348090839412";
        $template_name = "new_order_1";
        $url = "https://graph.facebook.com/v17.0/108752848993090/messages";
        
        $client = new \GuzzleHttp\Client();
        $headers = ["Authorization" => "Bearer " . env('WHATSAPP_TOKEN'), "Content-Type" => "application/json" ];
        // $params = ["messaging_product" =>  "whatsapp", "to" => $to, "type" => "template",
        // "template" => ["name" => $template_name, "language" => ["code" => "en_US"]]];
        
        $arr = array(
            "type" => "text",
            "text" => "28-05-2023"
        );
        $arr1 =array(
            "type" => "text",
            "text" => "Jozzy Stores"
        );
        $arr2 = array(
            "type" => "text",
            "text" => "234"
        );

        $myJSON = json_encode($arr);
        $myJSON2 = json_encode($arr1);
        $myJSON3 = json_encode($arr2);
    
        $params = [
            "messaging_product" =>  "whatsapp",
            "to" => $to,
            "type" => "template",
            "template" => [
                "namespace" => "46e87f57_c607_4e9f_b8f5_a8700ba35edb",
                "language" => [
                    "code" => "en_US"
                ],
                "name" => $template_name,
                "components" => [
                    
                    [
                        "type" => "body",
                        "parameters" => [
                            $myJSON,$myJSON2
                        ] 
                    # end body
                    ],
        
                    # The following part of this code example includes several possible button types, 
                    # not all are required for an interactive message template API call.
                    
                    [
                        "type" => "button",
                        "sub_type" => "url",
                        "index" => "0", 
                        "parameters" => [
                            $myJSON3
                        ]
                    ],
                    
                ]
            ]
        ];
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, ["headers" => $headers, "form_params" => $params]);
        
        $data = $response->getBody();
        return $data;
    }
}
