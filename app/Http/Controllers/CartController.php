<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\Cart;
use App\Models\Order;

class CartController extends Controller
{
    public function index()
    {
        try {
            if (Auth::check()) 
            {
                $items = Cart::with('product')->where(['user_id' => Auth::user()->id])->get()->toArray();
            }
            else
            {
                $items = session()->get('cart', []);
            }
            $title = "Cart";
            return view('cart',compact('items','title'));
        } catch (\Exception $e) {
            return catchReponse($e);
        }
    }
    public function addToCart($id)
    {
        try
        {
            if (Auth::check()) 
            {
                if (Cart::where(['product_id' => $id,'user_id' => Auth::user()->id])->exists()) 
                {
                    $cart_item = Cart::where(['product_id' => $id,'user_id' => Auth::user()->id])->first();
                    Cart::where(['product_id' => $id,'user_id' => Auth::user()->id])->update([
                        'qty' => $cart_item->qty++
                    ]);
                    $cart_item->save();
                }
                else
                {
                    Cart::create([
                        'product_id' => $id,
                        'qty' => 1,
                        'user_id' => Auth::user()->id
                    ]);
                }
            }
            else
            {
                $cart = session()->get('cart', []);

                if (isset($cart[$id])) {
                    $cart[$id]['qty']++;
                } else {
                    $cart[$id] = [
                        'id' => $id,
                        'qty' => 1,
                        'product' => Product::where('id',$id)->first()->toArray()
                    ];
                }
            
                session(['cart' => $cart]);
            }
            return Response::json(array(
                'error' => false,
                'errors' => null,
                'success' => true,
                'data' => [
                    'cart_count' => getCartCount(),
                ],
                'msg' => "Product added in cart successfully"
            ));
        } catch (\Exception $e) {
            return catchReponse($e);
        }
    }

    public function placeOrder()
    {
        try {
            $cart_details = Cart::with(['product'])->where(['user_id' => Auth::user()->id])->get();
            $total = 0;
            foreach ($cart_details as $key => $items) 
            {
                $total = $total + ($items->product->price * $items->qty);
            }
            $order = Order::create([
                'order_no' => "ORD000".(Order::max('id') + 1),
                'total' => $total,
                'user_id' => Auth::user()->id,
            ]);
            $order_items = [];
            foreach ($cart_details as $key => $items) 
            {
                array_push($order_items,[
                    'product_id' => $items->product_id,
                    'qty' => $items->qty,
                    'order_id' => $order->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            OrderItem::insert($order_items);
            Cart::where(['user_id' => Auth::user()->id])->delete();
            return Response::json(array(
                'error' => false,
                'errors' => null,
                'success' => true,
                'data' => [],
                'msg' => "",
                'route' => route('thank.you',['id' => $order->id])
            ));
        } catch (\Exception $e) {
            return catchReponse($e);
        }
    }

    public function thankYou($id)
    {
        try {
            $order_details = Order::where('id',$id)->first();
            $title = "Thank you";
            return view('thank_you',compact('title','order_details'));
        } catch (\Exception $e) {
            return catchReponse($e);
        }
    }
}
