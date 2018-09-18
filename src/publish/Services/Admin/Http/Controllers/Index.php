<?php

namespace App\Services\Admin\Http\Controllers;

class Index extends Base
{
    public function __invoke()
    {
        return $this->view();
    }
}
