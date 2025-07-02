<?php

namespace SaasPro\Features\Support;

use SaasPro\Features\Models\Feature;
use SaasPro\Support\State;

class FeatureState extends State {

    private Feature $feature;

    public function withFeature(Feature $feature) {
        $this->feature = $feature;
        return $this;
    }

}