<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanDirectResource\Pages;
use App\Filament\Resources\PlanDirectResource\RelationManagers;
use App\Models\PlanDirect;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanDirectResource extends Resource
{
    protected static ?string $model = PlanDirect::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Plan Settings';
    protected static ?int $navigationSort = 5;
    public static function getNavigationLabel(): string
    {
        return 'Referral';
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
                    ->default(0),
                Forms\Components\TextInput::make('level')
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('source')
                    ->maxLength(255),
                Select::make('commision_type')
                    ->options([
                        'percent' => 'Percent',
                        'fixed' => 'Fixed',                        
                    ])
                    ->required(),
                Forms\Components\TextInput::make('value')
                    ->numeric()
                    ->default(0.00000000),
                Forms\Components\Toggle::make('status'),
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
                Tables\Columns\TextColumn::make('level')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('commision_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListPlanDirects::route('/'),
            'create' => Pages\CreatePlanDirect::route('/create'),
            'edit' => Pages\EditPlanDirect::route('/{record}/edit'),
        ];
    }    
}
