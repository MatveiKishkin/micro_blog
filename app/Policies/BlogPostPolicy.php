<?php

namespace App\Policies;

use App\Models\BlogPost;
use App\Models\User;
use App\Repositories\BlogPost as BlogPostRepository;
use Illuminate\Auth\Access\HandlesAuthorization;
use ProfilanceGroup\BackendSdk\Support\Response;

class BlogPostPolicy
{
    use HandlesAuthorization;

    public function __construct(protected BlogPostRepository $blog_post_repository) {}

    /**
     * @param User $user
     * @param array $data
     * @return bool|array
     */
    public function update(User $user, array $data)
    {
        $blog_post = $this->blog_post_repository->find($data['blog_post_id']);

        if (empty($blog_post)) {
            return Response::error('Пост с указанным id не найден.');
        }

        return $user->id === $blog_post->user_id;
    }
}
