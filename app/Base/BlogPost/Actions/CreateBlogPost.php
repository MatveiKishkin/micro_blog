<?php

namespace App\Base\BlogPost\Actions;

use App\Models\BlogPost as BlogPostModel;
use App\Repositories\BlogPost as BlogPostRepository;
use ProfilanceGroup\BackendSdk\Exceptions\OperationError;
use ProfilanceGroup\BackendSdk\Support\Response;
use App\Base\Resources\PublicImages\BlogPost as BlogPostResources;
use Illuminate\Support\Facades\Auth;

class CreateBlogPost
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
    ) {}

    /**
     * Создание поста.
     *
     * @param array $data
     * @return BlogPostModel|array
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function create(array $data)
    {
        try {

            $user = Auth::user()->getModel();

            /** @var BlogPostModel $post */
            $post = $this->blog_post_repository->new();

            $post->user_id = $user->id;
            $post->title = $data['title'];
            $post->slug = $data['slug'];
            $post->content = $data['content'];

            if (!empty($data['image'])) {
                $post->addMedia($data['image'])->toMediaCollection('images');
            }

            $post->save();

            return $post;

        } catch (OperationError $e) {
            return Response::error($e->getMessage());
        }
    }
}