<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use App\Models\Transaction;

class OrderController extends Controller
{
    public static function store($request,$amounts,$token)
    {
        // dd($request->Order_details);
        $order = Order::query()->create([
            'user_id' => auth()->user()->id,
            'total_amount' => $amounts['total_amount'],
            'delivery_amount' => $amounts['delivery_amount'],
            'paying_amount' => $amounts['paying_amount'],
        ]);

        foreach($request->Order_details as $orders){
            $product = Product::findOrFail($orders['product_id']);
            Order_detail::query()->create([
               'order_id' => $order->id,
               'product_id' =>$product->id,
               'price' => $product->price,
               'quantity' => $orders['quantity'],
               'subtotal' => ($product->price * $orders['quantity']), 
            ]);
        }

        Transaction::query()->create([
            'user_id' => auth()->user()->id,
            'order_id' => $order->id,
            'amount' => $amounts['paying_amount'],
            'token' => $token,
            'request_from' => $request->request_from,
        ]);
    }

    public static function update($token,$transID)
    {
        $transation = Transaction::query()->where('token',$token)->firstOrFail();
        $transation->update([
           'status' => 1, //payment OK
           'trans_id' => $transID 
        ]);

        $order = Order::findOrFail($transation->order_id);
        $order->update([
            'status' => 1,
            'payment_status' => 1,
        ]);

        //mines productQuantity after transation:
        $orderDetail = Order_detail::query()->where('order_id',$order->id)->get();
        foreach($orderDetail as $item){
            $product = Product::find($item->product_id);
            $product->update([
                'quantity' => ($product->quantity - $item->quantity)
            ]);
        }
    }
}
