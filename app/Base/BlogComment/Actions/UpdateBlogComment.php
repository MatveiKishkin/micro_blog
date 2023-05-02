<?php

namespace App\Base\BlogPost\Actions;

use App\Models\BlogComment as BlogCommentModel;
use App\Repositories\BlogComment as BlogCommentRepository;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use ProfilanceGroup\BackendSdk\Exceptions\OperationError;
use ProfilanceGroup\BackendSdk\Support\Response;

class UpdateBlogPost
{

    /**
     * BlogPost constructor.
     *
     * @param BlogCommentRepository $blog_comment_repository
     */
    public function __construct(protected BlogCommentRepository $blog_comment_repository) {}

    /**
     * Редактирование поста.
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function update(array $data)
    {
        /** @var BlogCommentModel $comment */
        $comment = $this->blog_comment_repository->get($data['blog_post_id']);

//        if ($comment->slug != $data['slug'] && $this->blog_comment_repository->isExistsBySlug($data['slug'])) {
//            throw ValidationException::withMessages([
//                'blog_post' => ['Пост с указанным слагом уже существует.'],
//            ]);
//        }

        $comment->fill($data);

        $comment->save();

        return Response::success(null, ['blog_comment' => $comment]);
    }
}