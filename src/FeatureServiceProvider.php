<?php

namespace SaasPro\Features;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use SaasPro\Features\Facades\Features;
use SaasPro\Features\Commands\CreateFeature;
use SaasPro\Features\Contracts\InteractsWithFeatures;

class FeatureServiceProvider extends ServiceProvider {

    public function boot(): void{
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if($this->app->runningInConsole()){
            $this->commands([
                CreateFeature::class,
            ]);
        }
    }

    public function register(): void {
        Gate::before(function(InteractsWithFeatures $user, string $ability, mixed $arguments){
            if(!$feature = Features::from($ability)->feature()) throw new \Exception("Feature {$ability} does not exist");

            $response = $feature->check($user, $arguments);

            if($response->failed()) return Response::deny($response->message());
            return Response::allow();
        });
    }
    
}