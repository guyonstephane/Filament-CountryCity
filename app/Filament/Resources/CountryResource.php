<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers;
use App\Filament\Resources\CountryResource\RelationManagers\CitiesRelationManager;
use App\Models\City;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Attributes\Title;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('continent')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('population')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('esperanceVie')
                    ->numeric(),
                Forms\Components\TextInput::make('PNB')
                    ->numeric(),
                Forms\Components\TextInput::make('chefEtat')
                    ->maxLength(255),
                Forms\Components\TextInput::make('capitale')
                    ->formatStateUsing(fn (Country $record): string => City::find($record->capitale)->nom ?? 'no capitale')
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(255),
                
                   
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('continent')
                    ->searchable(),
                Tables\Columns\TextColumn::make('population')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('esperanceVie')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('PNB')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('chefEtat')
                    ->searchable(),
               Tables\Columns\TextColumn::make('capitale')
                   ->formatStateUsing(fn (Country $record): string => City::find($record->capitale)->nom ?? 'no capitale')
                   ->description(fn (Country $record): string => City::find($record->capitale)->nom ?? 'no capitale')
                   ->searchable(),
                Tables\Columns\TextColumn::make('code')
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
                Tables\Actions\ViewAction::make(),
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
            CitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'view' => Pages\ViewCountry::route('/{record}'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
