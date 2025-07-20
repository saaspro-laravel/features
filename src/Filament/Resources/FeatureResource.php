<?php

namespace SaasPro\Features\Filament\Resources;

use SaasPro\Enums\Timelines;
use SaasPro\Features\Filament\Resources\FeatureResource\Pages;
use SaasPro\Features\Models\Feature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use SaasPro\Filament\Forms\Components\SelectStatus;


class FeatureResource extends Resource
{
    protected static ?string $model = Feature::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Configuration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->default(null),
                Forms\Components\TextInput::make('reset_period')
                    ->maxLength(255)
                    ->default(null),
                Select::make('reset_interval')
                    ->options(Timelines::options())
                    ->native(false)
                    ->default(null),
                Forms\Components\TextInput::make('limit')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('unit')
                    ->maxLength(255)
                    ->default(null),
                SelectStatus::make('status')    
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->latest())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shortcode')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('description')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('reset_period')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reset_interval')
                    ->searchable(),
                Tables\Columns\TextColumn::make('limit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeatures::route('/'),
            // 'create' => Pages\CreateFeature::route('/create'),
            'edit' => Pages\EditFeature::route('/{record}/edit'),
        ];
    }
}
