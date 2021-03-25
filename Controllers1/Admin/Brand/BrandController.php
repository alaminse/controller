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
        $this->middleware('authadmin');
    }
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brand.brand', compact('brands'));
    }
    public function storebrand(Request $request)
    {
        $validateData = $request->validate([
            'brand_name' => 'required|unique:brands|max:55',
            'brand_logo' => 'required|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);

        $brand_name = $request->brand_name; 
        $brand_logo = $request->file('brand_logo');
        $brand_logo_Name = time().'.'.$brand_logo->extension();
        $brand_logo->move(public_path('media/brand/'),$brand_logo_Name);
        $logo_name = 'public/media/brand/'.$brand_logo_Name;

        $brand = new Brand();
        $brand->brand_name = $brand_name;
        $brand->brand_logo = $logo_name;
        $brand->save();
        $notification=array(
            'messege'=>'Brand Insert Successfully.',
            'alert-type'=>'success'
             );
        return Redirect()->back()->with($notification);
    }

    public function deletebrand($id)
    {
        $brand = Brand::find($id);
        unlink($brand->brand_logo);
        $brand->delete();
        $notification=array(
            'messege'=>'Brand Delete Successfully.',
            'alert-type'=>'warning'
             );
        return back()->with($notification);
    }
    public function Editbrand($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand.edit',compact('brand'));
    }
    public function UpdateBrand(Request $request)
    {
        $validateData = $request->validate([
            'brand_name' => 'required|unique:brands|max:55',
            'brand_logo' => 'required|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);
        $update = Brand::find($request->id);
        $update = $request->brand_name;
        $old_logo = $request->old_logo;
        $brand_logo = $request->file('brand_logo');
        if($request->has('brand_logo'))
        {
            unlink(public_path('media/brand/').$old_logo);
            
            $brand_logo_Name = time().'.'.$brand_logo->extension();
            $brand_logo->move(public_path('media/brand/'),$brand_logo_Name);
            $update->brand_logo = 'public/media/brand/'.$brand_logo_Name;
            $done = $update->save();
            if($done)
            {
                $notification=array(
                    'messege'=>'Brand Updated.',
                    'alert-type'=>'success'
                     );
                   return Redirect()->route('brands')->with($notification);
            }
            
        }
        else
        {
            $done = $update->save();
            if($done)
            {
                $notification=array(
                    'messege'=>'Brand Updated without Image.',
                    'alert-type'=>'success'
                );
                return Redirect()->route('brands')->with($notification);
            }
        }
    }
}
