<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = '/';

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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function register(Request $request)
    {
        // $this->validator($request->all())->validate();
        try {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone_no' => ['required', 'numeric', 'digits:10', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
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
                event(new Registered($user = $this->create($request->all())));

                $this->guard()->login($user);

                if ($response = $this->registered($request, $user)) {
                    return $response;
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
                    'route' => route('home')
                ));
            }
        } catch (\Exception $e) {
            return catchReponse($e);
        }
        
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_no' => $data['phone_no'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
