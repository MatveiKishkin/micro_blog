<?php

namespace App\Base\BlogPost\Actions;

use App\Base\Resources\PublicImages\BlogPost as BlogPostResources;
use App\Models\BlogPost as BlogPostModel;
use App\Repositories\BlogPost as BlogPostRepository;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use ProfilanceGroup\BackendSdk\Exceptions\OperationError;
use ProfilanceGroup\BackendSdk\Support\Response;

class UpdateBlogPost
{

    /**
     * BlogPost constructor.
     *
     * @param BlogPostRepository $blog_post_repository
     * @param BlogPostResources $blog_post_resources
     */
    public function __construct(protected BlogPostRepository $blog_post_repository, protected BlogPostResources $blog_post_resources) {}

    /**
     * Редактирование поста.
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function update(array $data)
    {
        /** @var BlogPostModel $post */
        $post = $this->blog_post_repository->get($data['blog_post_id']);

        if ($post->slug != $data['slug'] && $this->blog_post_repository->isExistsBySlug($data['slug'])) {
            throw ValidationException::withMessages([
                'blog_post' => ['Пост с указанным слагом уже существует.'],
            ]);
        }

        $post->fill($data);

        $post->save();

        return Response::success(null, ['blog_post' => $post]);
    }
}