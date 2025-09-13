<?php

namespace App\Modules\Admin\Http\Controllers\Auth;

use DB;
use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Modules\Admin\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use App\Modules\Company\Models\Company;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = 'admin-dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
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
            'email' => 'required',
            'password' => 'required',
        ]);
    }



    public function index()
    {

        if(Auth::check())
        {
            Session::flash('success', "You Have Already Logged In.");
            return redirect()->intended('admin-dashboard');

        }else{

            return view('Admin::auth.login');

        }


    }

    /*
     * Post_login
     */
    public function post_login(Requests\LoginRequest $request)
    {
        $data = $request->all();

        if(count($data)>0){
            if(Auth::check() )
            {

                $user_type = Auth::user()->type;

                Session::flash('success', "You Have Already Logged In.");
                return redirect()->intended($this->adminRedirectTo);

            }
            else
            {
                $user_data_exists = DB::table('users')->where('email', $data['email'])->where(function ($query) {
                            $query->where('type', '=', 'admin')
                           ->orWhere('type', '=', 'dealer')
                           ->orWhere('type', '=', 'outlet')
                           ->orWhere('type', '=', 'sub-dealer');
                })->exists();


                if($user_data_exists)
                {
                    $user_data = DB::table('users')->where('email', $data['email'])->where(function ($query) {
                            $query->where('type', '=', 'admin')
                           ->orWhere('type', '=', 'dealer')
                           ->orWhere('type', '=', 'outlet')
                           ->orWhere('type', '=', 'sub-dealer');
                })->first();
                    $check_password = Hash::check( $data['password'], $user_data->password);

                    //if password matched
                    if($check_password)
                    {
                        // dd($user_data->expiry_date);
                        //if user is exoiry date
                        if(date('Y-m-d')<=$user_data->expiry_date || $user_data->expiry_date ==null){

                        //if user is inactive
                        if($user_data->status=='inactive')
                        {
                            Session::flash('error', "Sorry! Your Account Is Inactive.Please Contact With Administrator To active Account.");
                        }
                        else
                        {
                            if(Auth::check())
                            {
                                Session::flash('success', "You are already Logged-in! ");
                            }else{
                                $attempt = Auth::attempt([
                                    'email' => $request->get('email'),
                                    'password' => $request->get('password'),
                                ]);

                                if($attempt)
                                {
                                    $company= Company::where('status','active')->first();
                                    $sidConfig= DB::table('sid_config')->first();
                                    Session::put('studentDefaultItem', $company->studentDefaultItem);
                                    Session::put('company_phone', $company->phone);
                                    Session::put('company_email', $company->email);
                                    Session::put('studentReadmissionDefaultItem', $company->studentReadmissionDefaultItem);
                                    Session::put('company_id', $company->id);
                                    Session::put('admit_card_design', $company->admit_card_design);
                                    Session::flash('success', "Successfully  Logged In.");

                                }else{
                                    Session::flash('danger', "Password Incorrect.Please Try Again");
                                }
                            }
                            return redirect()->intended('admin-dashboard');
                        }
                        }else{
                                    Session::flash('danger', "You login access is expired");
                                }
                    }else{
                        Session::flash('danger', "Password Incorrect.Please Try Again!!!");
                    }
                }else{
                    Session::flash('danger', "UserName/Email does not exists.Please Try Again");

                }
                return redirect()->back();
            }
        }else{
            Session::flash('danger', "UserName/Email does not exists.Please Try Again");
            return redirect()->back();
        }


    }
    public function resetpassword(){

        $pageTitle = 'Forget Password';

        return view('Admin::auth.passwords.forgetpass', [
            'pageTitle' => $pageTitle,
        ]);
    }

    public function change_form($slug)
    {
        $pageTitle = 'Forget Password';

        $data = User::where('users.remember_token', $slug)
                        ->select('users.*')
                        ->first();

        if (empty($data->remember_token)){

                Session::flash('danger', "Token not found.");
                return redirect('login');
        }else{

            return view('Admin::auth.passwords.password_reset_form', [
                'pageTitle' => $pageTitle,
                'data' => $data,

            ]);
        }

    }

    public function save_chage_password(Request $request){

        $user_data = \Auth::user();
        $input = $request->all();

        $check_password = $input['password'] === $input['password_confirmation'];
        if($check_password){

            $model=DB::table('users')
                    ->where('users.remember_token',$input['remember_token'])
                    ->update([
                        'password' => Hash::make($input['password']),
                        'remember_token' =>'',
                    ]);

            if($model){
                Session::flash('success', "Password changed successfully.");
                return redirect('login');
            }else{
                Session::flash('danger', "Unable to change password.");
            }
        }else{
            Session::flash('danger', "Do not match confirm password");
        }

        return redirect()->back();


    }


     /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/login');
    }
}
