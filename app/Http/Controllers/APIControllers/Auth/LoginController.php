<?php

namespace App\Http\Controllers\APIControllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        //check if user status is active
        $user = User::where('email',"{$request->email}")->first();
		if($user){
			if($user->user_status == 0){
            return redirect("login")->withFail('Sorry your account has been disabled. Contact System Admin');
			}
		}
        
     
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            $token = $user->createToken('myapptoken')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token,
            ];
    
            return response($response,201);
        }
    
        return response()->json(null, 401);
    }
}
