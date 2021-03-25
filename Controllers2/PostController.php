<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function create()
    {
        return view('auth.create');
    }
    public function insert(Request $request)
    {
        $title = $request->title;
        $details = $request->details;
        $photo = $request->file('photo');
        $photoName = time().'.'.$photo->extension();
        $photo->move(public_path('Images'),$photoName);

        $post = new Post();
        $post->title = $title;
        $post->details = $details;
        $post->photo = $photoName;
        $post->save();
        return back()->with("post_added","Record has been inserted");
    }
    public function view()
    {
        $posts = Post::all();
        return view('auth.postshow', compact('posts'));
    }
    public function singlePost($id)
    {
        $post = Post::find($id);
        return view('auth.singlePost',compact('post'));
    }

    // ---------------------
    public function edit($id)
    {
        $post = Post::find($id);
        return view('auth.postEdit',compact('post'));
    }
    
    public function update(Request $request)
    {
        $title = $request->title;
        $details = $request->details;
        $old_photo = $request->old_photo;
        $photo = $request->file('photo');

        $update = Post::find($request->id);
        if($request->has('photo'))
        {
            unlink(public_path('images').'/'.$old_photo);
            $photoName = time().'.'.$photo->extension();
            $photo->move(public_path('Images'),$photoName);
            $update->photo = $photoName;
        }
        $update->title = $title;
        $update->details = $details;
        $update->save();
        return back()->with("photo_updated","Record has been updated");
    }
    public function delete($id)
    {
        $post = Post::find($id);
        unlink(public_path('images').'/'.$post->photo);
        $post->delete();
        return back()->with("post_delete","Record has been deleted");
    }
    public function showpost()
    {
        $posts = Post::all();
        return view('welcome', compact('posts'));
    }

}
