<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function activate(Request $request){
        $package_id = $request->package_id;
        $package_subscription_days = $request->package_subscription_days;

        $subscription = new Subscription();
        $subscription->user_id = auth()->user()->user_account->id;
        $subscription->package_id = $package_id;
        $subscription->expires_at = Carbon::now()->addDays($package_subscription_days);

        $subscription->save();
    }

    public function verifySubscriptionPayment($ref){
        //verify in paystack 
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$ref,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer sk_live_fb2c2599ac88aacadceb6d44a228a0c75cb7f1e2",
            "Cache-Control: no-cache",
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }

        //check if successful
        $decodedResponse = json_decode($response);
        
        $status = $decodedResponse->data->status;

        if($status == 'success'){
            //save subscription
            Subscription::create([
                'user_id' => auth()->user()->user_account_id,
                'expires_at' => Carbon::now()->addDays(30),
                'package_id' => 2,
            ]);
            return redirect('/home')->with('status','You have successfully upgraded to HandySeam Premium');
        }
        if($status == 'failed'){
            return redirect('/home')->with('fail','There was an error. Contact support if you were debited');
        }
    }
}
