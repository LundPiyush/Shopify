<?php

namespace App\Http\Controllers;

use App\Products_model;
use App\ProductAtrr_model;
use App\ImageGallery_model;
use App\Category_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    public function index(){
        $products=Products_model::all();
//        echo "<pre>";
//        
//        print_r($products);
//        exit;
        return view('frontEnd.index',compact('products'));
    
    }
    public function detailpro($id){
        $detail_product=Products_model::findOrFail($id);
//       echo $detail_product;
        $imagesGalleries=ImageGallery_model::where('products_id',$id)->get();
//        echo $imagesGalleries;
        $totalStock=ProductAtrr_model::where('products_id',$id)->sum('stock');
        //echo $totalStock;
        
        $relateProducts=Products_model::where([['id','!=',$id],['categories_id',$detail_product->categories_id]])->get();
        //relateProducts same category id ka dusra product laake dega for recommedate items 
       return view('frontEnd.product_details',compact('detail_product','imagesGalleries','totalStock','relateProducts'));
    }
    public function shop(){
        $products=Products_model::all();
        $byCate="";
        return view('frontEnd.products',compact('products','byCate'));
    }
    public function listByCat($id){
        $list_product=Products_model::where('categories_id',$id)->get();
        $byCate=Category_model::select('name')->where('id',$id)->first();
        return view('frontEnd.products',compact('list_product','byCate'));

    }
    
    }
    ?>