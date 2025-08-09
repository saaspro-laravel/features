<?php

namespace SaasPro\Features\Concerns;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use SaasPro\Features\Models\Feature;

trait HasFeatureThrough {

    protected function featurePivotName(){
        return 'pivot';
    }

    protected function getFeaturePivotColumn(){
        return null;
    }

    function features(){
        if(!$pivotColumn = $this->getFeaturePivotColumn()) {
            throw new Exception("Property 'featurePivotColumn' must be defined in class ".$this::class);
        }

        $query = $this->belongsToMany(
            Feature::class, 
            'feature_items', 
            'featureable_id',
            'id',
            $pivotColumn
        )->as($this->featurePivotName());
        $query->withPivot(['limit', 'reset_period', 'reset_interval']);

        return $query;
    }

}