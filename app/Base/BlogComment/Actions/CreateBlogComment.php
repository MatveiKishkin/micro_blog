<?php

namespace App\Base\BlogComment\Actions;

use App\Models\BlogComment as BlogCommentModel;
use App\Repositories\BlogComment as BlogCommentRepository;
use ProfilanceGroup\BackendSdk\Exceptions\OperationError;
use ProfilanceGroup\BackendSdk\Support\Response;

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
     * @return array
     */
    public function create(array $data)
    {
        try {

            /** @var BlogCommentModel $post */
            $comment = $this->blog_comment_repository->new();

            $comment->user_id = \Auth::user()->getModel()->id;
            $comment->blog_post_id = $data['blog_post_id'];
            $comment->content = $data['content'];

            $post->save();

            return Response::success(null, ['blog_post' => $post]);


        } catch (OperationError $e) {
            return Response::error($e->getMessage());
        }
    }
}