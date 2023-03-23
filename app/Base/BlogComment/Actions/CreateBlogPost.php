<?php

namespace App\Base\BlogPost\Actions;

use App\Models\BlogPost as BlogPostModel;
use App\Repositories\BlogPost as BlogPostRepository;
use ProfilanceGroup\BackendSdk\Exceptions\OperationError;
use ProfilanceGroup\BackendSdk\Support\Response;
use App\Base\Resources\PublicImages\BlogPost as BlogPostResources;

class CreateBlogPost
{
    /**
     * BlogPost constructor.
     *
     * @param BlogPostRepository $blog_post_repository
     * @param BlogPostResources $blog_post_resources
     */
    public function __construct(protected BlogPostRepository $blog_post_repository, BlogPostResources $blog_post_resources) {}

    /**
     *  Создание поста.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data)
    {
        try {

            /** @var BlogPostModel $post */
            $post = $this->blog_post_repository->new();

            $post->user_id = $data['user_id'];
            $post->title = $data['title'];
            $post->slug = $data['slug'];
            $post->content = $data['content'];

            $post->image = 'https://source.unsplash.com/random/600x600';

            $post->save();

            return Response::success(null, ['blog_post' => $post]);


        } catch (OperationError $e) {
            return Response::error($e->getMessage());
        }
    }
}