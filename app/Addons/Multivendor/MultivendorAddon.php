<?php

namespace App\Addons\Multivendor;

use App\LaravelAddons\Addon;

class MultivendorAddon extends Addon
{
    public $name = 'Multivendor';

    public function boot()
    {
        $this->enableViews();
    }
}
