<?php

namespace SaasPro\Features\Support;

use SaasPro\Features\Contracts\InteractsWithFeatures;
use SaasPro\Features\Models\Feature;
use SaasPro\Support\State;

class FeatureState extends State {

    public Feature $feature;
    public ?InteractsWithFeatures $user;
    public Usage $usage;

    public function can(){
        return $this->isOk();
    }

}