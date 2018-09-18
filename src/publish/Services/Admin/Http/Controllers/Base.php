<?php

namespace App\Services\Admin\Http\Controllers;

use App\Http\Controllers\BaseController;

class Base extends BaseController
{
    public function __construct()
    {
        $this->setViewLayout('layouts.sidebar');
    }
}
