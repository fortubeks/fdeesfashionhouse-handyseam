<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Setting;
use App\Models\Subscription;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'numeric'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return;
          }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'user_type' => 'admin',
            'password' => Hash::make($data['password']),
        ]);

        if(isset($user)){
            $user->user_account_id = $user->id;
            $user->referral = $data['referral'];
            $user->reason = $data['reason'];
            $user->save();
            //create free subscription account
            $this->createFreeSubscription($user);
            if($user->user_type == 'admin'){
                $this->initializeUserSettings($user);

                //send welcome mail to user with video link
                
            }
        }
        return $user;
    }

    public function createFreeSubscription($user){
        $subscription = new Subscription;
        $subscription->user_id = $user->id;
        $subscription->package_id = 1;
        $subscription->expires_at = Carbon::now()->addDays(14);

        $subscription->save();
    }

    public function initializeUserSettings($user){
        if (!Setting::where('user_id', '=', $user->id)->exists()) {
            // setings not found
            //create and store new app setting and then redirect to page
            $setting = new Setting;
            $setting->user_id = $user->id;
            $setting->sms_api_key = 'a13babcd7b8dea714c3454f865f97d36ab76fbde';
            $setting->sms_api_username = 'fortubeks2010@hotmail.com';
            $setting->female_measurement_details = '{"bust":"Bust","waist":"Waist",
                "hips":"Hips","thigh":"Thigh","neck":"Neck","sleeve":"Sleeve"}';
            $setting->male_measurement_details = '{"chest":"Chest","waist":"Waist",
                "hips":"Hips","thigh":"Thigh","neck":"Neck","sleeve":"Sleeve","in_seam":"In Seam"}';
            $setting->measurement_details = '{"bust":"Bust","chest":"Chest","waist":"Waist",
                "hips":"Hips","thigh":"Thigh","neck":"Neck","sleeve":"Sleeve","in_seam":"In Seam"}';
            $setting->save();
          }
    }
}
