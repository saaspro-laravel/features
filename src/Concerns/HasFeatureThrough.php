<?php

namespace SaasPro\Features\Concerns;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use SaasPro\Features\Models\Feature;

trait HasFeatureThrough {

    protected function featurePivotName(){
        return 'feature_item';
    }

    protected function getFeaturePivotColumn(){
        return null;
    }

    protected function getFeaturePivotColumns(){
        return [];
    }

    function featurePivot(){
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

        if(!empty($this->getFeaturePivotColumns())) {
            $query->withPivot(...$this->getFeaturePivotColumns());
        }

        return $query;
    }

    function resolveFeatures(Collection $features): Collection {
        return $features;
    }

    function getFeaturesAttribute(){
        return $this->resolveFeatures($this->featurePivot);
    }

}