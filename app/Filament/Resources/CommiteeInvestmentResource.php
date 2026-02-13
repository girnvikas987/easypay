<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommiteeInvestmentResource\Pages;
use App\Filament\Resources\CommiteeInvestmentResource\RelationManagers;
use App\Models\CommiteeInvestment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommiteeInvestmentResource extends Resource
{
    protected static ?string $model = CommiteeInvestment::class;


    protected static ?string $navigationIcon = 'heroicon-s-banknotes';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'Investments';
    // protected static ?string $navigationGroup = 'Settings';

    public static function getNavigationLabel(): string
    {
        return 'Commitee`s';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('tx_user')
                    ->numeric()
                    ->default(null),
                Forms\Components\Select::make('package_id')
                    ->relationship('package', 'name')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('days')
                    ->maxLength(255)
                    ->default(0),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('tx_user')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('package.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('days')
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
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListCommiteeInvestments::route('/'),
            // 'create' => Pages\CreateCommiteeInvestment::route('/create'),
            'edit' => Pages\EditCommiteeInvestment::route('/{record}/edit'),
        ];
    }
}
