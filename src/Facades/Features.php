<?php

namespace SaasPro\Features\Facades;

use Illuminate\Support\Facades\Facade;

class Features extends Facade {
    
    protected static function getFacadeAccessor() {
        return \SaasPro\Features\Features::class;
    }

}