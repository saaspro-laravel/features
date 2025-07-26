<?php

namespace SaasPro\Features\Commands;

use SaasPro\Features\Models\Feature;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use SaasPro\Features\Facades\Features;

class CreateFeature extends GeneratorCommand implements PromptsForMissingInput
{
    
    protected $signature = 'make:feature 
                                {name : The name of the feature class} 
                                {--feature= : The unique key value of the feature}
                                {--title= : The name of the feature}
                                {--description= : The description of the feature}
                            ';

    protected $description = 'Create a new feature class';

    protected $type = 'Feature';

    function getStub(){
        return __DIR__.'/../../resources/stubs/features.stub';
    }

    protected function buildClass($name) {
        $options = $this->createFeature($this->argument('name'), $name);
        return str_replace(['{{feature}}'], [$options['feature']], parent::buildClass($name));
    }

    protected function getDefaultNamespace($rootNamespace) {
        return "{$rootNamespace}\Features";
    }

    protected function promptForMissingArgumentsUsing(){
        return [
            'feature' => 'The unique key value of the feature',
            'title' => 'The display name of the feature',
            'description' => 'The description of the feature'
        ];
    }

    function createFeature(string $name, string $class_name){
        $name = str($name)->beforeLast('Feature');

        $namespace = implode('\\', [app()->getNamespace(), $this->type, $name]);
        $title = $this->option('title') ?? $name->headline();
        $feature = $this->option('feature') ?? $name->snake();
        if(Features::from($feature)?->feature()) {
            $this->newLine();
            $this->error("Feature already exists");
            exit;
        }

        Feature::create([
            'name' => $title,
            'shortcode' => $feature,
            'feature_class' => $class_name,
            'description' => $this->option('description')
        ]);

        return compact('feature');
    }
}
