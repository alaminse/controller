<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Image;
use App\Models\Admin\Product\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('authadmin');
    }

    public function index()
    {
    	$product=DB::table('products')
        ->join('categories','products.category_id','categories.id')
        ->join('brands','products.brand_id','brands.id')
        ->select('products.*','categories.category_name','brands.brand_name')
        ->get();
        return view('admin.product.show',compact('product'));

    }

    public function create()
    {
    	$category=DB::table('categories')->get();
    	$brand=DB::table('brands')->get();
    	return view('admin.product.create',compact('category','brand'));
    }



  //subcategory collect by ajax request
    public function GetSubcat($category_id)
    {
    	$cat = DB::table("sub_categories")->where("category_id",$category_id)->get();
        return json_encode($cat);
    }

    public function store(Request $request)
    {
        // $data=array();
    	// $data['product_name']=$request->product_name;
    	// $data['product_code']=$request->product_code;
    	// $data['product_quantity']=$request->product_quantity;
    	// $data['category_id']=$request->category_id;
    	// $data['subcategory_id']=$request->subcategory_id;
    	// $data['brand_id']=$request->brand_id;
    	// $data['product_size']=$request->product_size;
    	// $data['product_color']=$request->product_color;
    	// $data['selling_price']=$request->selling_price;
    	// $data['product_details']=$request->product_details;
    	// $data['video_link']=$request->video_link;
    	// $data['main_slider']=$request->main_slider;
    	// $data['hot_deal']=$request->hot_deal;
    	// $data['best_rated']=$request->best_rated;
    	// $data['trend']=$request->trend;
    	// $data['mid_slider']=$request->mid_slider;
    	// $data['hot_new']=$request->hot_new;
    	// $data['status']=1;


    	// $image_one=$request->image_one;
    	// $image_two=$request->image_two;
    	// $image_three=$request->image_three;


        // if($image_one && $image_two && $image_three){
        //     $image_one_name= hexdec(uniqid()).'.'.$image_one->getClientOriginalExtension();
        //         Image::make($image_one)->save('public/media/product/'.$image_one_name);
        //         $data['image_one']='public/media/product/'.$image_one_name;

        //     $image_two_name= hexdec(uniqid()).'.'.$image_two->getClientOriginalExtension();
        //         Image::make($image_two)->save('public/media/product/'.$image_two_name);
        //         $data['image_two']='public/media/product/'.$image_two_name; 

        //     $image_three_name= hexdec(uniqid()).'.'.$image_three->getClientOriginalExtension();
        //         Image::make($image_three)->save('public/media/product/'.$image_three_name);
        //         $data['image_three']='public/media/product/'.$image_three_name; 
                
        //         $product=DB::table('products')
        //                   ->insert($data);
        //             $notification=array(
        //              'messege'=>'Successfully Product Inserted ',
        //              'alert-type'=>'success'
        //             );
        //         return Redirect()->back()->with($notification);   
        // }

        $validateData = $request->validate([
            'product_name' => 'required|max:55',
            'product_code' => 'required|max:55',
            'product_quantity' => 'required|max:55',
            'category_id' => 'required|max:25',
            'subcategory_id' => 'required|max:25',
            'brand_id' => 'required|max:25',
            'product_size' => 'required|max:25',
            'product_color' => 'required|max:25',
            'selling_price' => 'required|max:25',
            'product_details' => 'required|max:5000',
            'video_link' => 'max:5000',
            'main_slider' => 'max:25',
            'hot_deal' => 'max:25',
            'best_rated' => 'max:25',
            'trend' => 'max:25',
            'mid_slider' => 'max:25',
            'hot_new' => 'max:25',
            'image_one' => 'required|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'image_two' => 'required|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'image_three' => 'required|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);

        $product = new Product();

        
        $product->product_name = $request->product_name;
        $product->product_code = $request->product_code;
        $product->product_quantity = $request->product_quantity;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->brand_id = $request->brand_id;
        $product->product_size = $request->product_size;
        $product->product_color = $request->product_color;
        $product->selling_price = $request->selling_price;
        $product->product_details = $request->product_details;
        $product->video_link = $request->video_link;
        $product->main_slider = $request->main_slider;
        $product->hot_deal = $request->hot_deal;
        $product->best_rated = $request->best_rated;
        $product->trend = $request->trend;
        $product->mid_slider = $request->mid_slider;
        $product->hot_new = $request->hot_new;
        $product->status = 1;

   
        $image_one = $request->file('image_one');
        $image_two = $request->file('image_two');
        $image_three = $request->file('image_three');
        // dd($product);
        if($image_one && $image_two && $image_three)
        {

            
            $image_one_Name = hexdec(uniqid()).'.'.$image_one->extension();
            $image_one->move(public_path('media/product/'),$image_one_Name);
            $image_one = 'public/media/product/'.$image_one_Name;
            $product->image_one = $image_one;
            
            $image_two_Name =  hexdec(uniqid()).'.'.$image_two->extension();
            $image_two->move(public_path('media/product/'),$image_two_Name);
            $image_two = 'public/media/product/'.$image_two_Name;
            $product->image_two = $image_two;
            
            $image_three_Name =  hexdec(uniqid()).'.'.$image_three->extension();
            $image_three->move(public_path('media/product/'),$image_three_Name);
            $image_three = 'public/media/product/'.$image_three_Name;
            $product->image_three = $image_three;
            
            
            $product->save();
            $notification=array(
                'messege'=>'Product Insert Successfully.',
                'alert-type'=>'success'
            );
            return Redirect()->back()->with($notification);
        }
        else
        {
            $notification=array(
                'messege'=>'Product Image Not added.',
                'alert-type'=>'error'
            );
            return Redirect()->back()->with($notification);
        }
   
    }

    public function Inactive($id)
    {
         DB::table('products')->where('id',$id)->update(['status'=> 0]);
         $notification=array(
                     'messege'=>'Successfully Product Inactive ',
                     'alert-type'=>'success'
                    );
         return Redirect()->back()->with($notification);  
    }

    public function Active($id)
    {
         DB::table('products')->where('id',$id)->update(['status'=> 1]);
         $notification=array(
                     'messege'=>'Successfully Product Aactive ',
                     'alert-type'=>'success'
                    );
         return Redirect()->back()->with($notification);
    }

    public function DeleteProduct($id)
    {
        $product=DB::table('products')->where('id',$id)->first();
        $image1=$product->image_one;
        $image2=$product->image_two;
        $image3=$product->image_three;
        unlink($image1);
        unlink($image2);
        unlink($image3);
        DB::table('products')->where('id',$id)->delete();
        $notification=array(
                     'messege'=>'Successfully Product Deleted ',
                     'alert-type'=>'success'
                    );
         return Redirect()->back()->with($notification);

    }

    public function ViewProduct($id)
    {
        // echo($id);
        $product=DB::table('products')
                ->join('categories','products.category_id','categories.id')
                ->join('brands','products.brand_id','brands.id')
                ->join('sub_categories','products.subcategory_id','sub_categories.id')
                ->select('products.*','categories.category_name','brands.brand_name')
                ->select('products.*','categories.category_name','brands.brand_name','sub_categories.subcategory_name')
                ->where('products.id',$id)
                ->first();
                // return json_encode($product);
        return view('admin.product.view',compact('product'));

    }

    public function EditProduct($id)
    {
        $product=DB::table('products')->where('id',$id)->first();

        return view('admin.product.edit',compact('product'));
    }

    public function UpdateProductWithoutPhoto(Request $request,$id)
    {
        $data=array();
        $data['product_name']=$request->product_name;
        $data['product_code']=$request->product_code;
        $data['product_quantity']=$request->product_quantity;
        $data['category_id']=$request->category_id;
        $data['discount_price']=$request->discount_price;
        $data['subcategory_id']=$request->subcategory_id;
        $data['brand_id']=$request->brand_id;
        $data['product_size']=$request->product_size;
        $data['product_color']=$request->product_color;
        $data['selling_price']=$request->selling_price;
        $data['product_details']=$request->product_details;
        $data['video_link']=$request->video_link;
        $data['main_slider']=$request->main_slider;
        $data['hot_deal']=$request->hot_deal;
        $data['best_rated']=$request->best_rated;
        $data['trend']=$request->trend;
        $data['mid_slider']=$request->mid_slider;
        $data['hot_new']=$request->hot_new;
        
        $update=DB::table('products')->where('id',$id)->update($data);
        if ($update) {
             $notification=array(
                     'messege'=>'Successfully Product Updated ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);

        }else{
            $notification=array(
                     'messege'=>'Nothing To Updated ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);
        }
    }

    public function UpdateProductPhoto(Request $request,$id)
    {
        $old_one=$request->old_one;
        $old_two=$request->old_two;
        $old_three=$request->old_three;

        $image_one=$request->image_one;
        $image_two=$request->image_two;
        $image_three=$request->image_three;
        $data=array();

        if($request->has('image_one')) {
           unlink($old_one);
           $image_one_name= hexdec(uniqid()).'.'.$image_one->getClientOriginalExtension();
           Image::make($image_one)->save('public/media/product/'.$image_one_name);
           $data['image_one']='public/media/product/'.$image_one_name;
           DB::table('products')->where('id',$id)->update($data);
            $notification=array(
                     'messege'=>'Image One Updated ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);


        }if($request->has('image_two')) {
           unlink($old_two);
           $image_two_name= hexdec(uniqid()).'.'.$image_two->getClientOriginalExtension();
           Image::make($image_two)->save('public/media/product/'.$image_two_name);
           $data['image_two']='public/media/product/'.$image_two_name;
           DB::table('products')->where('id',$id)->update($data);
            $notification=array(
                     'messege'=>'Image Two Updated ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);
        }if($request->has('image_three')) {
           unlink($old_three);
           $image_three_name= hexdec(uniqid()).'.'.$image_three->getClientOriginalExtension();
           Image::make($image_three)->save('public/media/product/'.$image_three_name);
           $data['image_three']='public/media/product/'.$image_three_name;
           DB::table('products')->where('id',$id)->update($data);
            $notification=array(
                     'messege'=>'Image Three Updated ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);
        }if($request->has('image_one') && $request->has('image_two')){
            
           unlink($old_one);
           $image_one_name= hexdec(uniqid()).'.'.$image_one->getClientOriginalExtension();
           Image::make($image_one)->save('public/media/product/'.$image_one_name);
           $data['image_one']='public/media/product/'.$image_one_name;
            
           unlink($old_two); 
           $image_two_name= hexdec(uniqid()).'.'.$image_two->getClientOriginalExtension();
           Image::make($image_two)->save('public/media/product/'.$image_two_name);
           $data['image_two']='public/media/product/'.$image_two_name;

           DB::table('products')->where('id',$id)->update($data);
            $notification=array(
                     'messege'=>'Image One and Two Updated ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);
           


        }if($request->has('image_one') && $request->has('image_two') && $request->has('image_three')){
           unlink($old_one);
           unlink($old_two);
           unlink($old_three);
           $image_one_name= hexdec(uniqid()).'.'.$image_one->getClientOriginalExtension();
           Image::make($image_one)->save('public/media/product/'.$image_one_name);
           $data['image_one']='public/media/product/'.$image_one_name;
            
           $image_two_name= hexdec(uniqid()).'.'.$image_two->getClientOriginalExtension();
           Image::make($image_two)->save('public/media/product/'.$image_two_name);
           $data['image_two']='public/media/product/'.$image_two_name;

            $image_three_name= hexdec(uniqid()).'.'.$image_three->getClientOriginalExtension();
           Image::make($image_three)->save('public/media/product/'.$image_three_name);
           $data['image_three']='public/media/product/'.$image_three_name;
            DB::table('products')->where('id',$id)->update($data);
            $notification=array(
                     'messege'=>'Image One and Two Updated ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);
          

        }
         return Redirect()->route('all.product');
    }

}
