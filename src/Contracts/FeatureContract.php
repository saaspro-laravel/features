<?php

namespace SaasPro\Features\Contracts;

use SaasPro\Features\Models\Feature;
use SaasPro\Features\Models\FeatureUsage;
use SaasPro\Features\Support\Usage;
use SaasPro\Support\State;

interface FeatureContract {

    function validator(Feature $feature): State;
    function record(Usage $usage): void;

}