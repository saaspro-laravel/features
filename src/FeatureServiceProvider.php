<?php

namespace SaasPro\Features;

use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use SaasPro\Features\Facades\Features;
use SaasPro\Features\Commands\CreateFeature;
use SaasPro\Features\Contracts\InteractsWithFeatures;

class FeatureServiceProvider extends ServiceProvider {

    public function boot(): void{
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->commands([
            CreateFeature::class,
        ]);
    }

    public function register(): void {
        Features::authorize();
    }
    
}