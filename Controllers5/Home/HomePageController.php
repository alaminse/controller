<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Product\Product;
use DB;

class HomePageController extends Controller
{
    public function index()
    {
        $products=Product::take(10)->get();
        return view('welcome',compact('products'));
    }
    public function SingleProduct($id)
    {
        $product=DB::table('products')
                ->join('categories','products.category_id','categories.id')
                ->join('brands','products.brand_id','brands.id')
                // ->join('sub_categories','products.subcategory_id','sub_categories.id')
                ->select('products.*','categories.category_name','brands.brand_name')
                // ->select('products.*','categories.category_name','brands.brand_name','sub_categories.subcategory_name')
                ->where('products.id',$id)
                ->first();
                // return json_encode($product);
        return view('product.single',compact('product'));
    }
    public function Shop()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(6);
        // return json_encode($products);
        return view('pages.shop',compact('products'));
    }
    public function Contact()
    {
        return view('pages.contact');
    }
    public function About()
    {
        return view('pages.about');
    }
    public function Checkout()
    {
        return view('pages.checkout');
    }
}
