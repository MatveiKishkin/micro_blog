<?php

namespace App\Base\BlogPost;

use App\Repositories\BlogPost as BlogPostRepository;
use App\Models\BlogPost as BlogPostModel;
use ProfilanceGroup\BackendSdk\Exceptions\OperationError;
use ProfilanceGroup\BackendSdk\Support\Response;

class BlogPost
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

    /**
     * Удаление поста.
     *
     * @param int $id
     * @return array
     */
    public function destroy($id)
    {
        /* @var BlogPostModel $post */
        $post = $this->blog_post_repository->get($id);

        if(!empty($this->image_uploader)) {
            $this->image_uploader->removeImage($post->image);
        }

        $post->delete();

        return Response::success();
    }
}