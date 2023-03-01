<?php

namespace App\Base\BlogPost\Actions;

use App\Models\BlogPost as BlogPostModel;
use App\Repositories\BlogPost as BlogPostRepository;
use ProfilanceGroup\BackendSdk\Exceptions\OperationError;
use ProfilanceGroup\BackendSdk\Support\Response;

class BlogPostUpdate
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
     * Редактирование поста.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data)
    {
        try {

            /** @var BlogPostModel $post */
            $post = $this->blog_post_repository->get($data['id']);

            if ($post->slug != $data['slug'] && $this->blog_post_repository->isExistsBySlug($data['slug'])) {
                return Response::error('Пост с указанным слагом уже существует.');
            }

            $post->fill($data);

            if (!is_null($data['image']) && !empty($this->image_uploader)) {
                $this->image_uploader->removeImage($post->image);
                $post->image = $this->image_uploader->uploadImage($data['image']);
            }

            $post->save();

            return Response::success(null, ['blog_post' => $post]);

        } catch (OperationError $e) {
            return Response::error($e->getMessage());
        }
    }
}