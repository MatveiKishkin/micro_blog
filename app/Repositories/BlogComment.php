<?php

namespace App\Repositories;

use App\Models\BlogComment as BlogCommentModel;
use ProfilanceGroup\BackendSdk\Abstracts\Repository;

class BlogComment extends Repository
{
    /**
     * BlogComment constructor.
     */
    public function __construct()
    {
        $this->model = BlogCommentModel::class;
    }
}