<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Category\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $category = Category::all();
        return view('admin.category.categories', compact('category'));
    }
    
    public function storecategory(Request $request)
    {
        $validateData = $request->validate([
            'category_name' => 'required|unique:categories|max:55',
        ]);
        $category = new Category();
        $category->category_name = $request->category_name;
        $category->save();
        $notification=array(
            'messege'=>'Category Insert Done.',
            'alert-type'=>'success'
             );
           return Redirect()->back()->with($notification);
    }
    public function deletecategory($id)
    {
        
        $category = Category::find($id)->delete();
        $notification=array(
            'messege'=>'Category Successfully Deleted.',
            'alert-type'=>'warning'
             );
           return Redirect()->back()->with($notification);
    }
    public function EditCategory($id)
    {
        $category = Category::find($id);
        return view('admin.category.edit',compact('category'));
    }
    
    public function UpdateCategory(Request $request, $id)
    {
        $validateData = $request->validate([
            'category_name' => 'required|unique:categories|max:55',
        ]);
        $category_name = $request->category_name;
        $update = Category::find($request->id);
        $update->category_name = $category_name;
        $done = $update->save();
        if($done)
        {
            $notification=array(
                'messege'=>'Category Updated.',
                'alert-type'=>'success'
                 );
               return Redirect()->route('categories')->with($notification);
        }
        else
        {
            $notification=array(
                'messege'=>'Nothing to updated.',
                'alert-type'=>'wrong'
                 );
               return Redirect()->route('categories')->with($notification);
        }
        
    }

}
