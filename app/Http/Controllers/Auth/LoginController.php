<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        try {
            $rules = [
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string', 'min:8'],
            ];

            $validator = Validator::make($request->all(), $rules, [
                'required' => 'Required',
            ]);

            if ($validator->fails()) {
                return Response::json(array(
                    'error' => true,
                    'errors' => $validator->getMessageBag(),
                    'success' => false,
                    'msg' => "",
                ));
            } else {
                if (method_exists($this, 'hasTooManyLoginAttempts') &&
                    $this->hasTooManyLoginAttempts($request)) {
                    $this->fireLockoutEvent($request);

                    return $this->sendLockoutResponse($request);
                }

                if ($this->attemptLogin($request)) {
                    if ($request->hasSession()) {
                        $request->session()->put('auth.password_confirmed_at', time());
                    }

                    $cart = session()->get('cart', []);
                    $insert_data = [];
                    foreach ($cart as $key => $item) 
                    {
                        if ((Cart::where(['product_id' => $item['id'],'user_id' => Auth::user()->id])->exists())) 
                        {
                            Cart::where(['product_id' => $item['id'],'user_id' => Auth::user()->id])->update([
                                'qty' => ((int)Cart::where(['product_id' => $item['id'],'user_id' => Auth::user()->id])->first()->qty + (int)$item['qty'])
                            ]);
                        }
                        else
                        {
                            array_push($insert_data,[
                                'product_id' => $item['id'],
                                'qty' => $item['qty'],
                                'user_id' => Auth::user()->id,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                        }
                    }

                    if (!empty($insert_data)) 
                    {
                        Cart::insert($insert_data);
                    }

                    return Response::json(array(
                        'error' => false,
                        'errors' => null,
                        'success' => true,
                        'msg' => "",
                        'route' => (Auth::user()->is_admin) ? route('admin.home') : route('home')
                    ));
                }

                // If the login attempt was unsuccessful we will increment the number of attempts
                // to login and redirect the user back to the login form. Of course, when this
                // user surpasses their maximum number of attempts they will get locked out.
                return Response::json(array(
                    'error' => true,
                    'errors' => [
                        'email' => [
                            'These credentials do not match our records.'
                        ]
                    ],
                    'success' => false,
                    'msg' => "",
                ));
            }
        } catch (\Exception $e) {
            return catchReponse($e);
        }
    }
}
