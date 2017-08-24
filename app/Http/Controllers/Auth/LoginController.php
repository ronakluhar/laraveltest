<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use DB;
Use Helpers;
Use Config;

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
    protected $redirectTo = 'admin/users';
    
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->controller = 'LoginController';
    }
    
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }
    
    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        return $credentials;
    }
    
    /**
     * The user has been authenticated, update login entry from here.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        \App\User::where('id', Auth::user()->id)->update(['current_login_time' => strtotime(date('Y-m-d H:i:s')),'is_login' => 1]);
        //Create Audit Log
        Helpers::createAudit(Auth::user()->id, Auth::user()->is_admin, Config::get('constant.AUDIT_ACTION_LOGIN'), $this->controller . "@authenticated", $_SERVER['REQUEST_URI'], '', '', $_SERVER['REMOTE_ADDR']);
    }
    
    
    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];
        // Load user from database
        $user = \App\User::where($this->username(), $request->{$this->username()})->first();
        // Check if user was successfully loaded, that the password matches
        // and active is not 1. If so, override the default error message.
        if ($user && \Hash::check($request->password, $user->password) && $user->active != 1) {
            $errors = [$this->username() => 'Your account is not active.'];
        }
        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        \App\User::where('id', Auth::user()->id)->update(["last_logged_in_at" => DB::raw("current_login_time"),'is_login' => 0,'current_login_time' => NULL]);              
        //Create Audit Log
        Helpers::createAudit(Auth::user()->id, Auth::user()->is_admin, Config::get('constant.AUDIT_ACTION_LOGOUT'), $this->controller . "@logout", $_SERVER['REQUEST_URI'], '', '', $_SERVER['REMOTE_ADDR']);        
        $this->guard()->logout();
        $request->session()->invalidate();                                
        return redirect('/');
    }
}
