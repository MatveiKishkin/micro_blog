<?php

namespace App\Repositories;

use App\Models\Profile as ProfileModel;
use ProfilanceGroup\BackendSdk\Abstracts\Repository;

class Profile extends Repository
{
    /**
     * Profile constructor.
     */
    public function __construct()
    {
        $this->model = ProfileModel::class;
    }
}