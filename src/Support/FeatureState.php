<?php

namespace SaasPro\Features\Support;

use Illuminate\Database\Eloquent\Model;
use SaasPro\Features\Contracts\InteractsWithFeatures;
use SaasPro\Features\Models\Feature;
use SaasPro\Support\State;

class FeatureState extends State {

    public Feature $feature;
    public InteractsWithFeatures | Model | null $user = null;
    public Usage $usage;

    public function can(){
        return $this->isOk();
    }

}