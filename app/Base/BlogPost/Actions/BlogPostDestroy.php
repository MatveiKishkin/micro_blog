<?php

namespace App\Base\BlogPost\Actions;

use App\Models\BlogPost as BlogPostModel;
use App\Repositories\BlogPost as BlogPostRepository;
use ProfilanceGroup\BackendSdk\Support\Response;

class BlogPostDestroy
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