<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PriceControl;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Paynow\Payments\Paynow;

class SubscriptionContoller extends Controller
{
        public function makeSubscription(Request $request){
        $user = Auth::user();
        $request->validate([
            'phone_number' => 'required',
            'method' => 'required'
        ]);

        $paynow = new Paynow(
            $this->integration_id,
            $this->integration_key,
            'http://127.0.0.1:8000/gateways/paynow/update',
            'http://127.0.0.1:8000/return?gateway=paynow'
        );
        $package = PriceControl::all();
        $activePrice = $package[0];
        $payment = $paynow->createPayment('ORDER' . 'Ordernumber', $user->email);

        $payment->add('Price', $activePrice->subscription_price);

        $response = $paynow->sendMobile($payment, $request->input('phone_number'), $request->input('method'));
        if ($response->success) {
            //create transaction
            $Order = new Order();
            $Order->user_id = $user->id;
            $Order->payment_method = $request->input('method');
            $Order->amount = $activePrice->subscription_price;
            $Order->poll_url = $response->pollUrl();
            $Order->status = 'ordered';
            $Order->phone_number = $request->input('phone_number');
            $Order->save();

            return response()->json([
                'success' => true,
                'message' => $response->instructions(),
                'Order' => $Order
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response->error
            ]);
        }
    }

    public function checkPayment($id){

        $user = Auth::user();

        $paynow = new Paynow(
            $this->integration_id,
            $this->integration_key,
            'http://127.0.0.1:8000/gateways/paynow/update',
            'http://127.0.0.1:8000/return?gateway=paynow'
        );

        $transaction = Order::find($id);

        if ($transaction->poll_url == null){
            return response()->json([
                'success' => false,
                'message' => 'payment error'
            ], 217);
        }

        $status = $paynow->pollTransaction($transaction->poll_url);

        $response = $status->data();
        if ($status->paid()) {
            //check if the order was used before
            if($transaction->used){
                return response()->json([
                    'success' => false,
                    'message', 'this order was paid and used for an older subscription'
                ]);
            }else{
                //create or update a subscription
                if($user->subscription != null){
                    $today = Carbon::now();
                    if($user->subscription->expires_at > $today){
                        //its not yet expired but add more days
                        $subscription = Subscription::find($user->subscription->id);
                        $subscription->expires_at = $subscription->expires_at->addMonth(1);
                        $subscription->save();
                        $transaction->status = $response['status'];
                        $transaction->used = 1;
                        $transaction->save();
                        return response()->json([
                            'success' => true,
                            'status' => $response['status'],
                            'order'=>$transaction
                        ]);
                    }else{
                        //its expired just add a month
                        $carbon = Carbon::now()->addMonth(1);
                        $subscription = Subscription::find($user->subscription->id);
                        $subscription->expires_at = $carbon;
                        $subscription->save();
                        $transaction->status = $response['status'];
                        $transaction->used = 1;
                        $transaction->save();
                        return response()->json([
                            'success' => true,
                            'status' => $response['status'],
                            'order'=>$transaction
                        ]);
                    }
                }else{
                    //create a new subscription
                    $carbon = Carbon::now()->addMonth(1);
                    $subscription = new Subscription();
                    $subscription->user_id = $user->id;
                    $subscription->expires_at = $carbon;
                    $subscription->save();
                    $transaction->status = $response['status'];
                    $transaction->used = 1;
                    $transaction->save();
                    return response()->json([
                        'success' => true,
                        'status' => $response['status'],
                        'order'=>$transaction
                    ]);
                }
            }
        } else {
            $transaction->status = $response['status'];
            $transaction->save();
            return response()->json([
                'success' => false,
                'status' => $response['status'],
                'order'=>$transaction
            ]);
        }
    }

    public function getUserData(){
        return Auth::user();
    }
}
