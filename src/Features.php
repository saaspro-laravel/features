<?php

namespace SaasPro\Features;

use Exception;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use SaasPro\Features\Contracts\FeatureContract;
use SaasPro\Features\Contracts\InteractsWithFeatures;
use SaasPro\Features\Models\Feature;
use SaasPro\Features\Support\FeatureState;

class Features {

    public function authorize(){
        Gate::before(function(?InteractsWithFeatures $user, string $ability, mixed $arguments){
            if($feature = $this->from($ability)) {
                $response = $feature->forUser($user)->validate($arguments);
                if($response->failed()) {
                    return Response::deny($response->message());
                }
                return Response::allow();
            }
        });
    }

    public function from(Feature|string $feature) {
        if(is_string($feature)) {
            if(class_exists($feature)) {
                $feature = new $feature();
                
                if(!$feature instanceof FeatureContract) {
                    throw new Exception("Feature class {$feature} must be an instance of ".FeatureContract::class);
                }

                return $feature;
            }

            if($feature = Feature::whereKey($feature)->first()) {
                return $feature->instance;
            }
        }

        return null;
    }

    function validate(Feature|string $feature, ?InteractsWithFeatures $user = null): FeatureState {
        $feature = $this->from($feature);
        if($user) $feature->forUser($user);
        return $feature->validate();
    }

    function can(Feature|string $feature, ?InteractsWithFeatures $user = null): bool{
        return $this->validate($feature, $user)->isOk();
    }

}