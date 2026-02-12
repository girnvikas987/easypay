<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportResource\Pages;
use App\Filament\Resources\SupportResource\RelationManagers;
use App\Models\Support;
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
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;

class SupportResource extends Resource
{
    protected static ?string $model = Support::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?int $navigationSort = 8;
    protected static ?string $navigationGroup = 'Other';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            Section::make()
            ->columns(2)
                ->schema([
                     
                    
                    TextInput::make('subject'),
                    TextInput::make('message'),
                    Hidden::make('message'),
                    Textarea::make('response')
                        ->rows(10)
                        ->cols(20),
                     
                    Select::make('status')
                    ->options([
                        '1' => 'Resolved',
                        '0' => 'Pending',                        
                    ])
                    ->native(false)->required(),
                   
                   
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.username')->searchable()->label('Username'),
                Tables\Columns\TextColumn::make('user.name')->searchable()->label('Name'),
                
                Tables\Columns\TextColumn::make('user.mobile')->searchable()->label('Mobile'),
                Tables\Columns\TextColumn::make('user.email')->searchable()->label('Email'),
                Tables\Columns\TextColumn::make('subject')->searchable(),
                Tables\Columns\TextColumn::make('status'),
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
            'index' => Pages\ListSupports::route('/'),
            'create' => Pages\CreateSupport::route('/create'),
            'edit' => Pages\EditSupport::route('/{record}/edit'),
        ];
    }    
}
