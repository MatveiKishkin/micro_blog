<?php

namespace App\Http\Controllers;

use App\Repositories\BlogPost as BlogPostRepository;
use Illuminate\Http\Request;

class BlogPostController
{
    /**
     * @param BlogPostRepository $blog_post_repository
     */
    public function __construct(
        protected BlogPostRepository $blog_post_repository,
    ){}


    public function index(Request $request)
    {
        $posts = $this->blog_post_repository->getAllPosts();

        return view('welcome', ['posts' => $posts]);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'slug' => 'required|unique:blog_posts,slug|max:256',
            'title' => 'required|max:256',
            'content' => 'required',
            'image' => 'required|image'
        ]);

        $result = $this->blog_post_base->createPost($request);
    }

    public function update(Request $request)
    {
        //
    }
}