<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Template extends Facade
{
    protected static function getFacadeAccessor() { return 'app.template.template'; }
}
