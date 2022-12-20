<?php

namespace App\Repositories;

use App\Models\BlogTag as BlogTagModel;
use ProfilanceGroup\BackendSdk\Abstracts\Repository;

class BlogTag extends Repository
{
    /**
     * BlogTag constructor.
     */
    public function __construct()
    {
        $this->model = BlogTagModel::class;
    }
}