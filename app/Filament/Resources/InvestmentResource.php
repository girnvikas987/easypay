<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvestmentResource\Pages;
use App\Filament\Resources\InvestmentResource\RelationManagers;
use App\Models\Investment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table; 
use Webbingbrasil\FilamentDateFilter;
// use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\BaseFilter;
use App\Filament\Resources\DateRangeFilter;
class InvestmentResource extends Resource
{
    protected static ?string $model = Investment::class;

    protected static ?string $navigationIcon = 'heroicon-s-banknotes';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'Other';
    // protected static ?string $navigationGroup = 'Settings';

    public static function getNavigationLabel(): string
    {
        return 'Orders';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.mobile')->searchable()->label('Username'),
                Tables\Columns\TextColumn::make('user.name')->searchable(),
                Tables\Columns\TextColumn::make('amount'),
                
                Tables\Columns\IconColumn::make('status')  
                ->boolean(),
            ])
            ->filters([
              //  DateRangeFilter::make('created_at'),

            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Action::make('Download Invoice') 
                // ->url(fn (Investment $record) => route('invest.pdf', $record))
                // ->openUrlInNewTab()
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListInvestments::route('/'),
            // 'create' => Pages\CreateInvestment::route('/create'),
            // 'edit' => Pages\EditInvestment::route('/{record}/edit'),
        ];
    }    
}
