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

        return view('index', ['posts' => $posts]);
    }
}