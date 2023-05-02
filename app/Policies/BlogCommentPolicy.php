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
     * Обновление комментария.
     *
     * @param User $user
     * @param array $data
     * @return bool|array
     * @throws ValidationException
     */
    public function update(User $user, array $data)
    {
        $blog_comment = $this->blog_comment_repository->find($data['id']);

        if (empty($blog_comment)) {
            throw ValidationException::withMessages([
                'blog_comment' => ['Не найден комментарий с указанным id.'],
            ]);
        }

        return $user->id === $blog_comment->user_id;
    }

    /**
     * Удаление комментария.
     *
     * @param User $user
     * @param array $data
     * @return bool|array
     * @throws ValidationException
     */
    public function delete(User $user, array $data)
    {
        $blog_comment = $this->blog_comment_repository->find($data['id']);

        if (empty($blog_comment)) {
            throw ValidationException::withMessages([
                'blog_comment' => ['Не найден комментарий с указанным id.'],
            ]);
        }

        return $user->id === $blog_comment->user_id;
    }
}
