<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanRewardResource\Pages;
use App\Filament\Resources\PlanRewardResource\RelationManagers;
use App\Models\PlanReward;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanRewardResource extends Resource
{
    protected static ?string $model = PlanReward::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift-top';
    protected static ?string $navigationGroup = 'Plan Settings';
    protected static ?int $navigationSort = 5;
    public static function getNavigationLabel(): string
    {
        return 'Reward';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->columns(2)
                    ->schema([
                    Forms\Components\Select::make('wallet_type_id')
                        ->relationship('wallet', 'name')
                        ->required(),
                    Forms\Components\TextInput::make('direct_required')
                        ->numeric()
                        ->default(0.00),
                    Forms\Components\TextInput::make('generation_team_required')
                        ->numeric()
                        ->default(0.00),
                    Forms\Components\TextInput::make('self_business_required')
                        ->numeric()
                        ->default(0.00),
                    Forms\Components\TextInput::make('direct_business_required')
                        ->numeric()
                        ->default(0.00),
                    Forms\Components\TextInput::make('generation_business_required')
                        ->numeric()
                        ->default(0.00),
                    Forms\Components\TextInput::make('left_team_required')
                        ->numeric()
                        ->default(0.00),
                    Forms\Components\TextInput::make('right_team_required')
                        ->numeric()
                        ->default(0.00),
                    Forms\Components\TextInput::make('reward')
                        ->maxLength(200),
                    Forms\Components\TextInput::make('rank')
                        ->maxLength(200),
                    Forms\Components\Toggle::make('status')->inline(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('wallet.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('direct_required')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('generation_team_required')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('direct_business_required')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('generation_business_required')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('left_team_required')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('right_team_required')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reward')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rank')
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
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlanRewards::route('/'),
            'create' => Pages\CreatePlanReward::route('/create'),
            'edit' => Pages\EditPlanReward::route('/{record}/edit'),
        ];
    }    
}
