<?php

namespace SaasPro\Features\Filament\Resources\FeatureResource\Pages;

use Filament\Forms\Set;
use SaasPro\Features\Filament\Resources\FeatureResource;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

class ListFeatures extends ListRecords
{
    protected static string $resource = FeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Run Command')
                ->modalWidth('md')
                ->form([
                    TextInput::make('name')
                        ->prefix('php artisan make:feature')
                        ->unique('features', 'feature_class')
                        ->placeholder('YourFeatureClass')
                        ->label('Command')
                        ->afterStateUpdated(function(Set $set, string $state){
                            $set('--feature', str($state)->snake());
                            $set('--title', str($state)->headline());
                        })
                        ->live(onBlur: true)
                        ->required(),
                    TextInput::make('--title')
                        ->label("Title"),
                    TextInput::make('--feature')  
                        ->label("Slug")
                        ->unique('features', 'shortcode'),
                    Textarea::make('--description')
                        ->label("Description")
                ])
                ->action(function(array $data) {
                    Artisan::call("make:feature", $data);
                    Notification::make()
                        ->body('Feature Created Successfully')
                        ->success()->send();
                }),
        ];
    }
}
