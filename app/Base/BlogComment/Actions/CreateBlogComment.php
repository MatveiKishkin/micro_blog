<?php

namespace App\Base\BlogComment\Actions;

use App\Models\BlogComment as BlogCommentModel;
use App\Repositories\BlogComment as BlogCommentRepository;
use Illuminate\Support\Facades\Auth;
use ProfilanceGroup\BackendSdk\Exceptions\OperationError;
use ProfilanceGroup\BackendSdk\Support\Response;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class CreateBlogComment
{
    /**
     * BlogPost constructor.
     *
     * @param BlogCommentRepository $blog_comment_repository
     */
    public function __construct(protected BlogCommentRepository $blog_comment_repository) {}

    /**
     *  Создание поста.
     *
     * @param array $data
     * @return BlogCommentModel|array
     */
    public function create(array $data)
    {
        try {

            if (!Auth::check()) {
                return Response::error('Пользователь не авторизован');
            }

            $user = Auth::user()->getModel();

            /** @var BlogCommentModel $comment */
            $comment = $this->blog_comment_repository->new();

            $comment->user_id = $user->id;
            $comment->blog_post_id = $data['blog_post_id'];
            $comment->content = $data['content'];

            if (!empty($data['image'])) {
                $comment->addMedia($data['image'])->toMediaCollection('images');
            }

            $comment->save();

            return $comment;

        } catch (OperationError|FileIsTooBig|FileDoesNotExist $e) {
            return Response::error($e->getMessage());
        }
    }
}