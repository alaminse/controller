<?php

namespace App\Http\Controllers\Admin\SubCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Category\Category;
use App\Models\Admin\SubCategory\SubCategory;
use DB;

class SubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $category = Category::all();
        $subcategory = DB::table('sub_categories')
        ->join('categories','sub_categories.category_id','categories.id')
        ->select('sub_categories.*','categories.category_name')
        ->get();
        return view('admin.subcategory.subcategories', compact('category','subcategory'));
    }
    public function storesubcategory(Request $request)
    {
        $validateData = $request->validate([
            'category_id' => 'required',
            'subcategory_name' => 'required|max:55',
        ]);
        $subcategory = new SubCategory();
        $subcategory->category_id = $request->category_id;
        $subcategory->subcategory_name = $request->subcategory_name;
        $subcategory->save();
        $notification=array(
            'messege'=>'SubCategory Insert Done.',
            'alert-type'=>'success'
             );
           return Redirect()->back()->with($notification);
    }
    public function deletesubcategory($id)
    {
        $subcategory = SubCategory::find($id);
        $subcategory->delete();
        return back()->with("subcategory_delete","Record has been deleted");
    }
    
    public function Editsubcategory($id)
    {
        $subcategory = SubCategory::find($id);
        $category = Category::all();
        return view('admin.subcategory.edit',compact('subcategory','category'));
    }
    
    public function Updatesubcategory(Request $request, $id)
    {
        $validateData = $request->validate([
            'category_id' => 'required',
            'subcategory_name' => 'required|max:55',
        ]);
        $update = SubCategory::find($id);
        $update->category_id = $request->category_id;
        $update->subcategory_name = $request->subcategory_name;
        $done = $update->save();
        if($done)
        {
            $notification=array(
                'messege'=>'SubCategory Updated.',
                'alert-type'=>'success'
                 );
               return Redirect()->route('sub.categories')->with($notification);
        }
        else
        {
            $notification=array(
                'messege'=>'Nothing to updated.',
                'alert-type'=>'wrong'
                 );
               return Redirect()->route('subcategories')->with($notification);
        }

        
    }
}
