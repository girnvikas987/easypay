<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KycResource\Pages;
use App\Filament\Resources\KycResource\RelationManagers;
use App\Models\Kyc;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\SelectColumn;

use Illuminate\Database\Eloquent\SoftDeletingScope;

class KycResource extends Resource
{
    protected static ?string $model = Kyc::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                        ->relationship(name: 'user', titleAttribute: 'username')
                        ->searchable(['username']),
                Forms\Components\TextInput::make('aadhar_no')
                    ->maxLength(255),
                Forms\Components\Toggle::make('aadhar_status'),
                Forms\Components\TextInput::make('pan_no')
                    ->maxLength(255),
                Forms\Components\Toggle::make('pan_status'),
                Forms\Components\FileUpload::make('aadhar_front_image')
                     ->image()
                     ->directory('kyc'),
                Forms\Components\FileUpload::make('aadhar_back_image')
                     ->image()
                     ->directory('kyc'),
                Forms\Components\FileUpload::make('pan_image')
                    ->image()
                     ->directory('kyc'),
                Forms\Components\FileUpload::make('self_image')
                    ->image()
                     ->directory('kyc'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('aadhar_no')
                    ->searchable(),
                Tables\Columns\IconColumn::make('aadhar_status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('pan_no')
                    ->searchable(),
                Tables\Columns\IconColumn::make('pan_status')
                    ->boolean(),
                Tables\Columns\ImageColumn::make('pan_image')->square(),
               

                Tables\Columns\ImageColumn::make('aadhar_front_image')->square(),
                Tables\Columns\ImageColumn::make('aadhar_back_image')->square(),
                Tables\Columns\ImageColumn::make('self_image'),
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
            'index' => Pages\ListKycs::route('/'),
            'create' => Pages\CreateKyc::route('/create'),
            'edit' => Pages\EditKyc::route('/{record}/edit'),
        ];
    }
}
