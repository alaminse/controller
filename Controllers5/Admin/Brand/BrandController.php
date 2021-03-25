<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Brand\Brand;
use DB;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brand.brand', compact('brands'));
    }
    public function storebrand(Request $request)
    {

        // $validateData = $request->validate([
        //     'brand_name' => 'required|unique:brands|max:55',
        //     'brand_logo' => 'required|mimes:jpeg,png,jpg,gif,svg|max:5048',
        // ]);

        // $brand_name = $request->brand_name; 
        // $brand_logo = $request->file('brand_logo');
        // $brand_logo_Name = time().'.'.$brand_logo->extension();
        // $brand_logo->move(public_path('media/brand/'),$brand_logo_Name);
        // $logo_name = 'public/media/brand/'.$brand_logo_Name;

        // $brand = new Brand();
        // $brand->brand_name = $brand_name;
        // $brand->brand_logo = $logo_name;
        // $brand->save();
        // $notification=array(
        //     'messege'=>'Brand Insert Successfully.',
        //     'alert-type'=>'success'
        //      );
        // return Redirect()->back()->with($notification);
        $validatedData = $request->validate([
            'brand_name' => 'required|unique:brands|max:55',
            ]);
    
            $data=array();
            $data['brand_name']=$request->brand_name; 
            $image=$request->file('brand_logo');
                if ($image) {
                    // $image_name= str_random(5);
                    $image_name= date('dmy_H_s_i');
    
                    $ext=strtolower($image->getClientOriginalExtension());
                    $image_full_name=$image_name.'.'.$ext;
                    $upload_path='public/media/brand/';
                    $image_url=$upload_path.$image_full_name;
                    $success=$image->move($upload_path,$image_full_name);
                  
                    $data['brand_logo']=$image_url;
                    $brand=DB::table('brands')
                              ->insert($data);
                        $notification=array(
                         'messege'=>'Successfully Brand Inserted ',
                         'alert-type'=>'success'
                        );
                    return Redirect()->back()->with($notification);                      
                }else{
                  $brand=DB::table('brands')
                              ->insert($data);
                     $notification=array(
                         'messege'=>'Done!',
                         'alert-type'=>'success'
                          );
                    return Redirect()->back()->with($notification); 
                }
    }

    public function deletebrand($id)
    {
        $brand = Brand::find($id);
        unlink($brand->brand_logo);
        $brand->delete();
        return back()->with("brand_delete","Record has been deleted");
    }
    public function Editbrand($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand.edit',compact('brand'));
    }
    public function UpdateBrand(Request $request, $id)
    {
        $oldlogo=$request->old_logo;
        $data=array();
        $data['brand_name']=$request->brand_name;
        $image=$request->file('brand_logo');
            if ($image) {
                unlink($oldlogo);
                $image_name= date('dmy_H_s_i');
                $ext=strtolower($image->getClientOriginalExtension());
                $image_full_name=$image_name.'.'.$ext;
                $upload_path='public/media/brand/';
                $image_url=$upload_path.$image_full_name;
                $success=$image->move($upload_path,$image_full_name);
                $data['brand_logo']=$image_url;
                // dd($brand_name);150321_15_36_54.jpg
                $brand=DB::table('brands')->where('id',$id)->update($data);
                    $notification=array(
                     'messege'=>'Successfully Brand Updated ',
                     'alert-type'=>'success'
                    );
                return Redirect()->route('brand')->with($notification);                      
            }else{
                $brand=DB::table('brands')->where('id',$id)->update($data);
                 $notification=array(
                     'messege'=>'Update without image!',
                     'alert-type'=>'success'
                      );
                return Redirect()->route('brand')->with($notification); 
            }
    
    }
}
