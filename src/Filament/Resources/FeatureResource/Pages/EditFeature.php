<?php

namespace SaasPro\Features\Filament\Resources\FeatureResource\Pages;

use SaasPro\Features\Filament\Resources\FeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeature extends EditRecord
{
    protected static string $resource = FeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
