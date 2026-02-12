<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FundRequestMethodResource\Pages;
use App\Filament\Resources\FundRequestMethodResource\RelationManagers;
use App\Filament\Resources\FundRequestMethodResource\RelationManagers\OptionRelationManager;
use App\Models\FundRequestMethod;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FundRequestMethodResource extends Resource
{
    protected static ?string $model = FundRequestMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Fund Request';

    public static function getNavigationLabel(): string
{
    return 'Methods';
}

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make()
            ->columns(2)
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255),
                Forms\Components\Toggle::make('status'),
            ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
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
            OptionRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFundRequestMethods::route('/'),
            'create' => Pages\CreateFundRequestMethod::route('/create'),
            'edit' => Pages\EditFundRequestMethod::route('/{record}/edit'),
        ];
    }    
}
