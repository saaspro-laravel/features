<?php

namespace Utyemma\SaasPro\Features\Concerns;

use Illuminate\Support\Facades\Gate;
use Utyemma\SaasPro\Facades\Features;
use Utyemma\SaasPro\Features\Models\Feature;

trait HasFeature {

    function hasFeature(string $class){
        $response = Gate::inspect($class);
        if($response->denied()) state(false, $response->message());
        return state(true);
    }

    function canUse(Feature | string $feature, $context = []) {
        return Features::from($feature)->validate([
            'user' => $this,
            ...$context
        ]);
    }

}