<?php

namespace SaasPro\Features\Concerns;

use SaasPro\Features\Facades\Features;
use SaasPro\Features\Models\Feature;

trait HasFeature {

    function canUse(Feature | string $feature, $context = []) {
        return Features::from($feature)->validate();
    }

    function features(){
        return $this->belongsToMany(Feature::class, 'plan_features')
            ->withPivot(['id', 'limit', 'reset_period', 'reset_interval'])
            ->as('feature')
            ->withTimestamps();
    }

}