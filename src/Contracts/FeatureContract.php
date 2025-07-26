<?php

namespace SaasPro\Features\Contracts;

use SaasPro\Features\Models\Feature;
use SaasPro\Features\Support\FeatureState;
use SaasPro\Features\Support\Usage;

interface FeatureContract {

    function validator(FeatureState $state): FeatureState;
    function record(Usage $usage): void;

}