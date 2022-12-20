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
     * If exists by slug.
     *
     * @param string $slug
     * @return bool
     */
    public function isExistsBySlug($slug)
    {
        return $this->query()
            ->where(compact('slug'))
            ->exists();
    }
}