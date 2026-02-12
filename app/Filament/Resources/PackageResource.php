<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Filament\Resources\PackageResource\RelationManagers;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Other';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make()
            ->columns(2)
                ->schema([
                     
                    TextInput::make('name')->required(),
                    TextInput::make('amount')->required(),
                    TextInput::make('min')->required(),
                    TextInput::make('max')->required(),
                     
                    Select::make('type')
                    ->options([
                        'manual' => 'Manual',
                        'fix' => 'Fix',                        
                    ])
                    ->native(false)->required(),
                    Select::make('no_of_time')
                    ->options([
                        'once' => 'Once',
                        'multiple' => 'Multiple',                        
                    ])
                    ->native(false)->required(),
                    Toggle::make('status')
                    ->onIcon('heroicon-m-bolt')
                    ->offIcon('heroicon-m-user')
                    ->inline(false),
                    Toggle::make('pre_reqired')
                    ->onIcon('heroicon-m-bolt')
                    ->offIcon('heroicon-m-user')
                    ->inline(false),
                   
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('min'),
                Tables\Columns\TextColumn::make('max'),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\ToggleColumn::make('status'),
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
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }    
}
