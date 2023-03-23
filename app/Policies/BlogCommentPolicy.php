<?php

namespace App\Policies;

use App\Models\User;
use App\Repositories\BlogComment as BlogCommentRepository;
use Illuminate\Auth\Access\HandlesAuthorization;
use Nuwave\Lighthouse\Exceptions\ValidationException;

class BlogCommentPolicy
{
    use HandlesAuthorization;

    public function __construct(protected BlogCommentRepository $blog_comment_repository) {}

    /**
     * @param User $user
     * @param array $data
     * @return bool|array
     * @throws ValidationException
     */
    public function create(User $user, array $data)
    {
        $blog_post = $this->blog_comment_repository->find($data['blog_post_id']);

        if (empty($blog_post)) {
            throw ValidationException::withMessages([
                'blog_post' => ['Не найден пост с указанным id.'],
            ]);
        }

        return $user->id === $blog_post->user_id;
    }
}
