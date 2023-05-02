<?php

namespace App\Base\BlogPost;

use App\Models\BlogPost as BlogPostModel;
use App\Repositories\BlogPost as BlogPostRepository;
use ProfilanceGroup\BackendSdk\Exceptions\OperationError;
use ProfilanceGroup\BackendSdk\Support\Response;
use App\Base\Resources\PublicImages\BlogPost as BlogPostResources;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;

class BlogPost
{
    /**
     * BlogPost constructor.
     *
     * @param BlogPostRepository $blog_post_repository
     * @param BlogPostResources $blog_post_resources
     */
    public function __construct(
        protected BlogPostRepository $blog_post_repository,
        protected BlogPostResources $blog_post_resources,
    )
    {

    }

    /**
     * Получение поста по id.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getPostById($data)
    {
        if (!empty($data['blog_post_id'])) {
            return $this->blog_post_repository->getPostByIdOrSlug($data['blog_post_id'], true);
        }

        return $this->blog_post_repository->getPostByIdOrSlug($data['slug']);
    }
}