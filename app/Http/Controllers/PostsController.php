<?php namespace Funblr\Http\Controllers;

use Funblr\Handlers\ImagesHandler;
use Funblr\Http\Requests\CreatePostRequest;
use Funblr\Post;
use Funblr\User;


class PostsController extends ApiController
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->user->posts;
        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Funblr\Http\Requests\CreatePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        $file = $request->file('image');
        $imgHandler = new ImagesHandler($this->user, $file);
        list($name, $url) = $imgHandler->upload();
        if ($url)
        {
            $this->user->posts()->save(Post::create([
                'title'=> $request->input('title'),
                'image_url' => $url,
                'name' => $name,
                'user_id' => $this->user->id,
            ]));
        }

        return response()->json(['image_url' => $url]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {   
        return response()->json($post);
    }

}
