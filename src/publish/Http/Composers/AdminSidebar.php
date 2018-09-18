<?php

namespace App\Http\Composers;

use Illuminate\Contracts\View\View;
use JumpGate\Menu\DropDown;
use JumpGate\Menu\Link;

class AdminSidebar
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $menu = \Menu::getMenu('adminMenu');

        $menu->link('admin.dashboard', function (Link $link) {
            $link->name = 'Admin Dashboard';
            $link->url  = route('admin.index');
        });
        $menu->link('admin.artisan', function (Link $link) {
            $link->name = 'Artisan';
            $link->url  = route('admin.artisan.index');
        });
    }
}
