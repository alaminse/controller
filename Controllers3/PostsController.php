<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post\Posts;

class PostsController extends Controller
{
    public function view()
    {
        return view('admin.create_post');
    }
    public function create_post(Request $request)
    {
        $uid = $request->uid;
        $utype = $request->utype;
        $action = $request->action;
        $title = $request->title;
        $details = $request->details;
        $profile_photo_path = $request->file('profile_photo_path');
        $imageName = time().'.'.$profile_photo_path->extension();
        $profile_photo_path->move(public_path('Images'),$imageName);

        $post = new Posts();
        $post->uid = $uid;
        $post->utype = $utype;
        $post->action = $action;
        $post->title = $title;
        $post->details = $details;
        $post->profile_photo_path = $imageName;
        $post->save();
        return back()->with("image_added","Record has been inserted");
    }
    public function show()
    {
        $user = Auth::user();
        $posts = Posts::where('uid',$user->id)->get(); 
        return view('admin.all_posts', compact('posts'));
    }
}
