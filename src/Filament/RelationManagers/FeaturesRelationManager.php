<?php

namespace SaasPro\Features\Filament\RelationManagers;

use Carbon\CarbonInterval;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use SaasPro\Enums\Timelines;
use SaasPro\Subscriptions\DataObjects\SubscriptionData;
use SaasPro\Subscriptions\Models\Plan;
use SaasPro\Subscriptions\Models\PlanPrice;
use SaasPro\Subscriptions\Models\Subscription;

class FeaturesRelationManager extends RelationManager
{
    protected static string $relationship = 'features';

    public function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make('pivot_limit')
                    ->label('Limit')
                    ->numeric(),
                Forms\Components\TextInput::make('pivot_reset_period')
                    ->label('Reset Period'),
                Forms\Components\Select::make('pivot_reset_interval')
                    ->label('Reset Interval')
                    ->native(false)
                    ->options(Timelines::options())
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user_id')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Feature'),
                Tables\Columns\TextColumn::make('limit')
                    ->state(fn(Model $record) => $record->pivot->limit ?? $record->limit),
                Tables\Columns\TextColumn::make('resets')
                    ->state(function(Model $record) {
                        $period = $record->pivot?->reset_period ?? $record->reset_period;
                        $interval = $record->pivot->reset_interval ?? $record->reset_interval;

                        if(!$period || !$interval) return '';
                        return "Every ".CarbonInterval::make($period, $interval);
                    })
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->latest());
    }
}
