<?php

namespace App\Http\Controllers;

use App\Coupon_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function applycoupon(Request $request){
    $this->validate($request,[
            'coupon_code'=>'required'
        ]);
        $input_data=$request->all();
        $coupon_code=$input_data['coupon_code'];
        $total_amount_price=$input_data['Total_amountPrice'];
    $check_coupon=Coupon_model::where('coupon_code',$coupon_code)->count();
    if($check_coupon==0){
        return back()->with('message_coupon','Your Coupon Code Not Exist!');
    }
    else if($check_coupon==1){
        $check_status=Coupon_model::where('status',1)->first();
        if($check_status->status==0){
                return back()->with('message_coupon','Your Coupon was Disabled!');
    }
    else{
        $expiried_date=$check_status->expiry_date;
                $date_now=date('Y-m-d');
                if($expiried_date<$date_now){
                    return back()->with('message_coupon','Your Coupon was Expired!');
                }else{
                    $discount_amount_price=($total_amount_price*$check_status->amount)/100;
                    Session::put('discount_amount_price',$discount_amount_price);
                    Session::put('coupon_code',$check_status->coupon_code);
                    return back()->with('message_apply_sucess','Your Coupon Code was Apply');
                }
    }
}
}
}