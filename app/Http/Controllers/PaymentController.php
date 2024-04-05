<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends ApiController
{
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'user_id' => 'required',
            'Order_details' => 'required',
            'Order_details.*.product_id' => 'required|integer',
            'Order_details.*.quantity' => 'required|integer',
            'request_from' => 'required',
        ]);
        if($validation->fails()){
            return $this->errorResponse(422,$validation->messages());
        }

        $totalAmount = 0;
        $deliveryAmount = 0;
        foreach($request->Order_details as $orders){
            $product = Product::findOrFail($orders['product_id']);
            if($product->quantity < $orders['quantity']){
                return $this->errorResponse(422,'the product quantity is incorrect!');
            }
            $totalAmount += $product->price * $orders['quantity'];
            $deliveryAmount += $product->delivery_amount;
        }
        $payingAmount = $totalAmount + $deliveryAmount;
        $amounts = [
            'total_amount' => $totalAmount,
            'delivery_amount' => $deliveryAmount,
            'paying_amount' => $payingAmount,
        ];

        $api = env('API_KEY');
        $amount = $payingAmount.'0';
        $mobile = "شماره موبایل";
        $factorNumber = "شماره فاکتور";
        $description = "توضیحات";
        $redirect = env('CALLBACK_URL');
        $result = $this->sendRequest($api, $amount, $redirect, $mobile, $factorNumber, $description);
        $result = json_decode($result);
        if($result->status) {
            OrderController::store($request,$amounts,$result->token);
            $go = "https://pay.ir/pg/$result->token";
           return $this->successResponse(200,['url'=>$go]);
        } else {
            return $this->errorResponse(422,$result->errorMessage);
        }
    }

    public function verify(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'token' => 'required',
            'status' => 'required',
        ]);
        if($validation->fails()){
            return $this->errorResponse(422,$validation->messages());
        }
        $api = env('API_KEY');
        $token = $request->token;
        $result = json_decode($this->verifyRequest($api,$token));
        // return response()->json($result);
        if(isset($result->status)){
            if($result->status == 1){
                if(Transaction::where('trans_id',$result->transId)->exists()){
                    return $this->errorResponse(422,'تراکنش قبلا به ثبت رسیده است');
                }
                OrderController::update($token,$result->transId);
                return $this->successResponse(200,'تراکنش با موفقیت به ثبت رسید');
            } else {
               return $this->errorResponse(422,'تراکنش با خطا مواجه شد');
            }
        } else {
            if($request->status == 0){
                return $this->errorResponse(422,'تراکنش با خطا مواجه شد');
            }
        }
    }

    public function sendRequest($api, $amount, $redirect, $mobile = null, $factorNumber = null, $description = null) 
    {
        return $this->curl_post('https://pay.ir/pg/send', [
            'api'          => $api,
            'amount'       => $amount,
            'redirect'     => $redirect,
            'mobile'       => $mobile,
            'factorNumber' => $factorNumber,
            'description'  => $description,
        ]);
    }

    public function verifyRequest($api,$token)
    {
        return $this->curl_post('https://pay.ir/pg/verify', [
            'api' 	=> $api,
            'token' => $token,
        ]);
    }

    public function curl_post($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }
}
