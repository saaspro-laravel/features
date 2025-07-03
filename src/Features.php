<?php

namespace SaasPro\Features;

use SaasPro\Features\Models\Feature;
use SaasPro\Features\Support\FeatureState;

class Features {

    private ?Feature $feature;

    public function __construct(Feature|string|null $feature = null) {
        if(is_string($feature)) {
            $this->fromKey($feature);
        }

        if($feature !== null) {
            $this->feature = $feature;
        }
    }

    public static function from(Feature|string $feature) {
        return new self($feature);
    }

    public function fromKey($key) {
        $this->feature = Feature::where('feature_class', $key)->orWhere('shortcode', $key)->first();
        return $this;
    }

    public function feature(){
        return $this->feature;
    }

    public function instance(){
        return $this->feature()?->instance();
    }

    public function usage(){
        return $this->feature->usage();
    }

    public function validate(array $context = []): FeatureState {        
        return $this->feature()->instance->validate($context);     
    }

    public function use(){
        $this->feature()->instance->use($this->usage());
    } 

}