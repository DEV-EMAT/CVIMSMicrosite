<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use File;
use Illuminate\Support\Facades\Log;
use Gate;

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
    
    protected function authenticated(Request $request, $user)
    {
        // dd(Auth::user()->device_identifier);
        if(Auth::user()->account_status == 1 
            && identifierCredentials(Auth::user()->device_identifier, 'web_account', 'hash_check') 
            && Auth::user()->email_verified_at != null){
            
        // if(Auth::user()->account_status == 1 && Auth::user()->device_identifier != 2){
        // if(Auth::user()->account_status == 1){
    
            // Log::channel('logs')->notice('Showing user profile for user 1: '.$user);
            // File::append(storage_path('logs/ecabs.log'), 'asdsad');

            // activity logs
            action_log('LOG IN', 'LOGGED IN');
            
            return redirect('covidtracer/dashboard');
        }else
        {
            if(Auth::user()->account_status != 1){
                $errors = [$this->username() => trans('Your Account is not active')];
            }
            if(Auth::user()->email_verified_at == null){
                $errors = [$this->username() => trans('Please Verify your email to proceed.')];
            }
            Auth::logout();
            return redirect('/login')->withErrors($errors);
        }
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request){
        
        // activity logs
        action_log('LOG OUT', 'LOGGED OUT');
        Auth::logout();

        return response()->json(['success' => true]);
    }

    protected function credentials(Request $request)
    {
        $username = str_replace(' ', '', $request->get('email'));

        if(is_numeric($username)){
            if(strlen($username) == 11){
                $username = substr_replace($username, '+63', 0, 1);

                return ['contact_number'=>$username,'password'=>$request->get('password')];
            } else {
                $username = substr_replace($username, '+63', 0, 3);
                
                return ['contact_number'=>$username,'password'=>$request->get('password')];
            }
        }
        elseif (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $username, 'password'=>$request->get('password')];
        }
        return ['email' => $username, 'password'=>$request->get('password')];
    }


    
    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
}
