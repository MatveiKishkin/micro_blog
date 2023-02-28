<?php

namespace App\Base\BlogPost\Actions;

use App\Models\BlogPost as BlogPostModel;
use App\Repositories\BlogPost as BlogPostRepository;
use ProfilanceGroup\BackendSdk\Exceptions\OperationError;
use ProfilanceGroup\BackendSdk\Support\Response;

class BlogPostCreate
{

    /**
     * @var \App\Contracts\ImageUploader
     */
    protected $image_uploader;

    /**
     * BlogPost constructor.
     *
     * @param BlogPostRepository $blog_post_repository
     */
    public function __construct(protected BlogPostRepository $blog_post_repository) {
        if(!empty(config('blog::image_uploader'))) {
            $this->image_uploader = app(config('blog::image_uploader'));
        }
    }

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

            if (!empty($this->image_uploader)) {
                $post->image = $this->image_uploader->uploadImage($data['image']);
            }

            $post->save();

            return Response::success(null, ['blog_post' => $post]);


        } catch (OperationError $e) {
            return Response::error($e->getMessage());
        }
    }
}