<?php

namespace App\Repositories;

use App\Models\BlogPost as BlogPostModel;
use ProfilanceGroup\BackendSdk\Abstracts\Repository;

class BlogPost extends Repository
{
    /**
     * BlogPost constructor.
     */
    public function __construct()
    {
        $this->model = BlogPostModel::class;
    }

    /**
     * Проверка существования поста по слагу.
     *
     * @param $slug
     * @return bool
     */
    public function isExistsBySlug($slug)
    {
        return $this->query()
            ->where(compact('slug'))
            ->exists();
    }

    /**
     * Получение всех постов блога.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllPosts()
    {
        return $this->query()
            ->where('created_at', '<=', now())
            ->get();
    }
}