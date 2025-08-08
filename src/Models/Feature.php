<?php

namespace SaasPro\Features\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\InteractsWithPivotTable;
use SaasPro\Concerns\HasEnums;
use SaasPro\Concerns\Models\HasStatus;
use SaasPro\Enums\Status;
use SaasPro\Features\Support\Usage;

class Feature extends Model {
    use HasStatus;

    protected $fillable = ['name', 'shortcode', 'description', 'feature_class', 'reset_period', 'reset_interval', 'limit', 'unit'];

    protected $casts = [
        'status' => Status::class
    ];


    function history(){
        return $this->hasMany(FeatureUsage::class);
    }

    function getInstanceAttribute(){
        return app($this->feature_class);
    }

    function check($user, $arguments = []) {
        return $this->instance->check($user, $arguments);
    }

    function getThresholdAttribute(){
        return $this->limit;
    }

    function getPeriodAttribute(){
        return $this->reset_period;
    }

    function resetPeriod(Carbon|null|string $date = null) {
        $date ??= now();
        return $date->subtract($this->interval, $this->period);
    }

    function interval($prefix = '') {
        $interval = str($this->interval)->lower();
        if($this->period == 1) return "per {$interval}";
        return "every {$this->period} ".$interval->plural($this->period);
    }

    function getIntervalAttribute(){
        if (isset($this->feature) && $this->feature->reset_interval !== null) {
            return $this->feature->reset_interval;
        }

        return $this->reset_interval;
    }

    function usage(){
        return Usage::for($this);
    }

}
