<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;

class PostController extends Controller
{
    private $post;
    private $category;

    public function __construct(Post $post,Category $category){
        $this->post=$post;
        $this->category=$category;
    }

    public function create(){
        $all_categories=$this->category->all();
        //SELECT*FROM categories;

        return view('users.posts.create')->with('all_categories',$all_categories);
    }

    public function store(Request $request){
        #Validate the data
        $request->validate([
            'category'=>'required|array|between:1,3',
            'description'=>'required|min:1|max:1000',
            'image'=>'required|file|mimes:jpeg,jpg,png,gif,mp4,m4v,mov|max:1048576'
        ]);

        #Save the post details
        $this->post->user_id=Auth::user()->id; //owner of the post
        // $this->post->image='data:image/'.$request->image->extension().';base64,';

        if($request->hasfile('image')){
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();
            $mimeType=$file->getMimeType();

            $prefix=strpos($mimeType,'video') !== false ? 'data:video/':'data:image/';
            $mediaData=$prefix.$extension.';base64,'.base64_encode(file_get_contents($file));

            $this->post->image=$mediaData;
        }

        // base64_encode(file_get_contents($request->image));
        $this->post->description=$request->description;
        $this->post->save();

        #Save the categories to the category_post table
        foreach($request->category as $category_id){
            $category_post[]=['category_id'=>$category_id];
        }

        $this->post->categoryPost()->createMany($category_post);

        #Go back to the homepage
        return redirect()->route('index');
    }

    public function show($id){
        $post=$this->post->findOrFail($id);
        //Same as:SELECT*FROM posts WHERE id=$id;

        return view('users.posts.show')->with('post',$post);
    }

    public function edit($id){
        $post=$this->post->findOrFail($id);

        #If the Auth user is not the ownerof the post,redirect to homepage.
        if(Auth::user()->id != $post->user->id){
            return redirect()->route('index');
        }

        #Get all the categories from the categories table
        $all_categories=$this->category->all(); //same as :SELECT*FROM categories;

        #Get all the category IDs of this post.Save it in an array
        $selected_categories=[];

        foreach($post->categoryPost as $category_post){
            $selected_categories[]=$category_post->category_id;
        }

        return view('users.posts.edit')
            ->with('post',$post)
            ->with('all_categories',$all_categories)
            ->with('selected_categories',$selected_categories);
    }

    public function update(Request $request,$id){
        #1.Validate the data
        $request->validate([
            'category'=>'required|array|between:1,3',
            'description'=>'required|min:1|max:1000',
            'image'=>'mimes:jpg,jpeg,gif,png|max:1048'
        ]);

        #2.Update the post
        $post=$this->post->findOrFail($id); //SELECT*FROM posts WHERE id=$id
        $post->description=$request->description;
        
        #3.Check if there is new image uploaded
        if($request->image){
            $post->image='data:image/'.$request->image->extension().';base64,'.
            base64_encode(file_get_contents($request->image));
        }

        $post->save();  //SAME AS:UPDATE posts SET description='$request->description',
                        //image='$request->image' WHERE id=$id;

        #DELETE all records from category_post related to this post
        $post->categoryPost()->delete(); //Same as :DELETE FROM category_post WHERE post_id=$id
        //Use the relationship Post::categoryPost() to select the records related to a post

        #4.Save the new categories selected by the user to category_post table
        foreach($request->category as $category_id){
            $category_post[]=['category_id'=>$category_id];
        }
        $post->categoryPost()->createMany($category_post);

        #5.Redirect to Show Post Page(to confirm the update)
        return redirect()->route('post.show',$id);
    }

    public function destroy($id){
        $post=$this->post->findOrFail($id);
        $post->forceDelete(); //this will delete the post details in the table

        // $this->post->destroy($id);
        return redirect()->route('index');
    }
}