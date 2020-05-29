<?php

namespace App\Http\Controllers;

use App\Cart_model;
use App\ProductAtrr_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class CartController extends Controller
{
    public function index(){
        $session_id=Session::get('session_id');
        
        $cart_datas=Cart_model::where('session_id',$session_id)->get();
    
        $total_price=0;
        foreach ($cart_datas as $cart_data){
            
            $total_price+=$cart_data->price*$cart_data->quantity;
        }
        return view('frontEnd.cart',compact('cart_datas','total_price'));
    
    }
public function addToCart(Request $request){
    #print_r($request->all());
    $inputToCart=$request->all();
//    $data=$request->session->all();
//    print_r($data);
        Session::forget('discount_amount_price');
        Session::forget('coupon_code');
        if($inputToCart['size']==""){
            return back()->with('message','Please select Size');
        }
    else{
        print_r($request->all());
        $stockAvailable=DB::table('product_att')->select('stock','sku')->where(['products_id'=>$inputToCart['products_id'],
                'price'=>$inputToCart['price']])->first();
        print_r($stockAvailable);
        exit;
            if($stockAvailable->stock>=$inputToCart['quantity']){
                $inputToCart['user_email']='shopify@gmail.com';
                $session_id=Session::get('session_id');
                if(empty($session_id)){
                    $session_id=str_random(40);
                    Session::put('session_id',$session_id);
                }
                //print_r(session()->all());
                $inputToCart['session_id']=$session_id;
                $sizeAtrr=explode("-",$inputToCart['size']);
//                explode() : Break a string into an array:
//   input: $str = "Hello world. It's a beautiful day.";
//          print_r (explode(" ",$str));
// output:     Array ( [0] => Hello [1] => world. [2] => It's [3] => a [4] => beautiful [5] => day. )
                $inputToCart['size']=$sizeAtrr[1];
                $inputToCart['product_code']=$stockAvailable->sku;
                $count_duplicateItems=Cart_model::where(['products_id'=>$inputToCart['products_id'],
                    'product_color'=>$inputToCart['product_color'],
                    'size'=>$inputToCart['size']])->count();
//                echo $count_duplicateItems;
//                exit;
                 if($count_duplicateItems>0){
                    return back()->with('message','This Item Added already');
                    }
                else{
                    Cart_model::create($inputToCart);
                    return back()->with('message','Add To Cart');
                    }
            }
        else{
            return back()->with('message','Stock is not Available!');
           }
        }//end of else
}//end of function
    
    
    public function updateQuantity($id,$quantity){
//        print_r($quantity);
//        exit;
        Session::forget('discount_amount_price');
        Session::forget('coupon_code');
    
    $sku_size=DB::table('cart')->select('product_code','size','quantity')->where('id',$id)->first();
        //print_r($sku_size);
    $stockAvailable=DB::table('product_att')->select('stock')->where(['sku'=>$sku_size->product_code,'size'=>$sku_size->size])->first();
    //print_r($stockAvailable);
        $updated_quantity=$sku_size->quantity+$quantity;
        if($stockAvailable->stock>=$updated_quantity){
            DB::table('cart')->where('id',$id)->increment('quantity',$quantity);
            return back()->with('message','Quantity Updated');
        }else{
            return back()->with('message','Stock is not Available!');
        }

        
    }
    
    







}//end of class
    ?>