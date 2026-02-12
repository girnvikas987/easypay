<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Filament\Resources\PaymentMethodResource\RelationManagers;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextColumn;
use App\Filament\Resources\PaymentMethodResource\RelationManagers\OptionRelationManager;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationIcon = 'heroicon-s-qr-code';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Other';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->columns(2)
                    ->schema([
                         
                        TextInput::make('title')->required(),
                        TextInput::make('name')->required(), 
                         
                        FileUpload::make('image')
                        ->image() // Ensure only image files are uploaded
                        ->directory('assets/images') // Set the directory where the images will be stored
                        ->preserveFilenames() // Preserve the original filenames
                        ->required() // Make it a required field
                        ->afterStateUpdated(function ($state, $set) {
                            // Ensure $state is an array, even if only a single file is uploaded
                            if (is_array($state) && !empty($state)) {
                                // Get the first file path from the array (since you might upload only one file)
                                $filePath = $state[0]; 
                                
                                // Generate the full URL for the uploaded file
                                $imageUrl = 'https://earnfarmx.com/' . Storage::url('assets/images/' . $filePath);
                                
                                // Update the 'image' field with the full URL
                                $set('image', $imageUrl);
                            }
                        }),
                        Toggle::make('status')
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
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                ImageColumn::make('image') // 'image' refers to the column in your database
                ->label('Image') // Label for the image column
                ->defaultImageUrl('path/to/default/image.jpg'),
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
            OptionRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentMethods::route('/'),
            // 'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }    
}
