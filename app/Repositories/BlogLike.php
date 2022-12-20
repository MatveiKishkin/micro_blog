<?php

namespace App\Repositories;

use App\Models\BlogLike as BlogLikeModel;
use ProfilanceGroup\BackendSdk\Abstracts\Repository;

class BlogLike extends Repository
{
    /**
     * BlogLike constructor.
     */
    public function __construct()
    {
        $this->model = BlogLikeModel::class;
    }
}