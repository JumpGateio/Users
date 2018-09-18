<?php

namespace App\Services\Admin\Http\Controllers;

use App\Services\Admin\Commands\Index as AdminIndex;

class Index extends Base
{
    public function __invoke(AdminIndex $manager)
    {
        $this->setViewData($manager->load());

        return $this->view();
    }
}
