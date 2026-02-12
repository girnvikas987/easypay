<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function newQuery(): Builder
    {
        // Fetch only the 'SuperAdmin' record
        return parent::newQuery()->where('name', 'SuperAdmin');
    }
    
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([ 
                TextInput::make('email'), 
                TextInput::make('password')
                ->label('New Password')
                ->password()
                ->minLength(8)
                ->maxLength(255)
                ->dehydrateStateUsing(fn ($state) => $state ? Hash::make($state) : null)
                ->required(fn (string $context) => $context === 'create')
                ->nullable(fn (string $context) => $context === 'edit')
                ->dehydrated(fn ($state) => filled($state)), // Only save if a password is provided

                
        

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

        ->modifyQueryUsing(function (Builder $query) {
            return $query
                ->where('name', 'SuperAdmin'); 
 
        }) 
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable(), 
                Tables\Columns\TextColumn::make('email')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->sortable(), 
            ])
            ->filters([
                // Tables\Filters\Filter::make('SuperAdmin')
                // ->query(fn (Builder $query) => $query->where('name', 'SuperAdmin'))
                // ->default(), // Optionally set this as the default filter
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAdmins::route('/'),
            // 'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
