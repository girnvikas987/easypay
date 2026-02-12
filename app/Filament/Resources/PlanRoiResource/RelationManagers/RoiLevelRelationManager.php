<?php

namespace App\Filament\Resources\PlanRoiResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoiLevelRelationManager extends RelationManager
{
    protected static string $relationship = 'roiLevel';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('direct_required')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('level')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('source')
                    ->required()
                    ->maxLength(255),                
                Select::make('commision_type')
                            ->options([
                                'percent' => 'Percent',
                                'fixed' => 'Fixed',                        
                            ])
                            ->required(),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('status')->inline(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('value')
            ->columns([
                Tables\Columns\TextColumn::make('direct_required'),
                Tables\Columns\TextColumn::make('level'),
                Tables\Columns\TextColumn::make('source'),
                Tables\Columns\TextColumn::make('commision_type'),
                Tables\Columns\TextColumn::make('value'),
                Tables\Columns\ToggleColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
