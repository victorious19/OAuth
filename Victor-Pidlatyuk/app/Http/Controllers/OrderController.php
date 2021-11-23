<?php

namespace App\Http\Controllers;

use App\Filters\OrderFilter;
use App\Mail\SendMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Exception;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    function get_all(OrderFilter $request) {
        return auth()->user()->orders()->filter($request)->orderBy('created_at', 'DESC')->paginate(10);
    }
    function create(OrderRequest $request) {
        try {
            $product = Product::find($request["product_id"]);
            Stripe::charges()->create([
                'amount' => $product->price,
                'currency' => 'UAH',
                'source' => $request->stripeToken,
                'description' => $product->title
            ]);

            $request["product"] = $product->title;
            $request["price"] = $product->price;
            $request["user_id"] = $product->user_id;

            $order = Order::create($request->all());
            $this->send_user_email($request->user_email, $order);

            $admin = $product->user()->first();
            $this->send_admin_email($admin->email, $order);

            return response(['order' => $order, 'status' => 1], 201);
        } catch(Exception $exception) {
            return response(['status' => 0, 'message' => $exception->getMessage()], 201);
        }
    }
    function send_user_email($user_email, $order) {
        $data = [
            'title' => 'Your password',
            'hi' => 'Hello user!',
            'content' => 'You can pick up your order #'.$order->id.' "'.$order->product.'" in the amount of '.$order->price.' UAH tomorrow'
        ];
        Mail::to($user_email)->send(new SendMail($data));
    }
    function send_admin_email($admin_email, $order) {
        $data = [
            'title' => 'Your order from GoodShop',
            'hi' => 'Hello user!',
            'content' => 'User made order #'.$order->id.' "'.$order->product.'" in the amount of '.
                $order->price.' UAH. User email - '.$order->user_email.', full name - '.$order->user_full_name.
                ', phone number - '.$order->phone.'.'
        ];
        Mail::to($admin_email)->send(new SendMail($data));
    }
    function change_status(OrderRequest $request, $order_id) {
        return Order::find($order_id)->update(['status' => $request->status]);
    }
}
