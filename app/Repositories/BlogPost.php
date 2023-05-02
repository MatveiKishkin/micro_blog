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

    /**
     * Получение поста по id.
     *
     * @param int|string $item
     * @param bool $is_id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getPostByIdOrSlug($item, $is_id = false)
    {
        $builder = $this->query()
            ->where('created_at', '<=', now());

        if ($is_id) {
            $builder->where('id', $item);
        } else {
            $builder->where('slug', $item);
        }

        return $builder->first();
    }
}