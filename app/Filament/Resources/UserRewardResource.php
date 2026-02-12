<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserRewardResource\Pages;
use App\Filament\Resources\UserRewardResource\RelationManagers;
use App\Models\UserReward;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserRewardResource extends Resource
{
    protected static ?string $model = UserReward::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make()
            ->columns(2)
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('plan_reward_id')
                ->relationship('reward', 'reward')
                ->required(),
                    
                Forms\Components\TextInput::make('reward')
                    ->maxLength(255),
            ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),                
                Tables\Columns\TextColumn::make('reward')
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
            'index' => Pages\ListUserRewards::route('/'),
            'create' => Pages\CreateUserReward::route('/create'),
            'edit' => Pages\EditUserReward::route('/{record}/edit'),
        ];
    }    
}
