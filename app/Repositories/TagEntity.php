<?php

namespace App\Repositories;

use App\Models\TagEntity as TagEntityModel;
use ProfilanceGroup\BackendSdk\Abstracts\Repository;

class TagEntity extends Repository
{
    /**
     * TagEntity constructor.
     */
    public function __construct()
    {
        $this->model = TagEntityModel::class;
    }
}