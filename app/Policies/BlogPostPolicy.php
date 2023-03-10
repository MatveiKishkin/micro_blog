<?php

namespace App\Policies;

use App\Models\BlogPost;
use App\Models\User;
use App\Repositories\BlogPost as BlogPostRepository;
use Illuminate\Auth\Access\HandlesAuthorization;
use Nuwave\Lighthouse\Exceptions\ValidationException;

class BlogPostPolicy
{
    use HandlesAuthorization;

    public function __construct(protected BlogPostRepository $blog_post_repository) {}

    /**
     * @param User $user
     * @param array $data
     * @return bool|array
     * @throws ValidationException
     */
    public function update(User $user, array $data)
    {
        $blog_post = $this->blog_post_repository->find($data['blog_post_id']);

        if (empty($blog_post)) {
            throw ValidationException::withMessages([
                'blog_post' => ['Не найден пост с указанным id.'],
            ]);
        }

        return $user->id === $blog_post->user_id;
    }
}
