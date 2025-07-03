<?php

namespace SaasPro\Features\Concerns;

use Illuminate\Support\Facades\Gate;
use SaasPro\Features\Facades\Features;
use SaasPro\Features\Models\Feature;

trait HasFeature {

    function canUse(Feature | string $feature, $context = []) {
        return Features::from($feature)->validate();
    }

}