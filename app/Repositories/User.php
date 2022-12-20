<?php

namespace App\Repositories;

use App\Models\User as UserModel;
use ProfilanceGroup\BackendSdk\Abstracts\Repository;

class User extends Repository
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->model = UserModel::class;
    }
}