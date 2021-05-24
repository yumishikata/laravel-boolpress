<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Post;
use App\Tag;
use App\Category;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'posts' => Post::all()
        ];

        return view('admin.posts.index', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'tags' => Tag::all(),
            'categories' => Category::all()
        ];
        return view('admin.posts.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);
        
        $data = $request->all();
        $new_post = new Post();

        $new_post->fill($data);
        $slug = Str::slug($new_post->title, '-');
            $slug_base = $slug;
            //Verifico che slug non sia presente nel database
            $post_presente = Post::where('slug', $slug)->first();
            $contatore = 1;
            while($post_presente){
                $slug=$slug_base .'-' . $contatore;
                $contatore++;
                $post_presente = Post::where('slug', $slug)->first();

            }
            $new_post->slug = $slug;
            $new_post->user_id = Auth::id();


        $new_post->save();

        if(array_key_exists('tags', $data)) {
            $new_post->tags()->sync($data['tags']);
        }

        return redirect()->route('admin.posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::where('id', $id)->first();

        if (!$post) {
            abort(404);
        }

        $data = ['post' => $post];
        return view('admin.posts.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $data = [
            'post'=> $post,
            'tags'=> Tag::all()
        ];
        return view('admin.posts.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->all();
        if($data['title'] != $post->title){
            $slug = Str::slug($data['title'], '-');
            $slug_base = $slug;
            //Verifico che slug non sia presente nel database
            $post_presente = Post::where('slug', $slug)->first();
            $contatore = 1;
            while($post_presente){
                $slug=$slug_base .'-' . $contatore;
                $contatore++;
                $post_presente = Post::where('slug', $slug)->first();

            }
            $data['slug'] = $slug;
        }

        $post->update($data);

        if(array_key_exists('tags', $data)){
            $post->tags()->sync($data['tags']);
        }else {
            $post->tags()->sync([]);

        }

        return redirect()->route('admin.posts.show', $post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->tags()->sync([]);
        $post->delete();
        return redirect()->route('admin.posts.index');
    }
}
