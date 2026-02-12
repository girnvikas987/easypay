<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Components\MultiSelect;
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
use Filament\Forms\Components\TimePicker;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->columns(2)
                    ->schema([
                         
                        TextInput::make('title')->required(),
                        TextInput::make('type')->required()->unique(ignoreRecord: true),
                        TextInput::make('value')->required(),
                        MultiSelect::make('days')
                        ->label('Withdrawal Days')
                        ->options([
                            'monday' => 'Monday',
                            'tuesday' => 'Tuesday',
                            'wednesday' => 'Wednesday',
                            'thursday' => 'Thursday',
                            'friday' => 'Friday',
                            'saturday' => 'Saturday',
                            'sunday' => 'Sunday',
                        ])
                        ->required(),

                        TimePicker::make('from_time')
                            ->label('Start Time')
                            ->required()
                            ->format('H:i'), // Use the format 'H:i' for 24-hour time format

                        TimePicker::make('end_time')
                            ->label('End Time')
                            ->required()
                            ->format('H:i'), // Use the format 'H:i' for 24-hour time format


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
                Tables\Columns\TextColumn::make('type')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('value')->searchable(),
                Tables\Columns\TextColumn::make('days') // Display days column
                ->label('Withdrawal Days')
                ->formatStateUsing(fn ($state) => ucfirst(str_replace(',', ', ', $state))) // Format the string to be more readable
                ->searchable(),
                Tables\Columns\TextColumn::make('from_time') // Display 'time_from' column
                ->label('Time From')
                ->formatStateUsing(fn ($state) => date('h:i A', strtotime($state))) // Format the time as 12-hour format
                ->searchable(),
                Tables\Columns\TextColumn::make('end_time') // Display 'time_to' column
                ->label('Time To')
                ->formatStateUsing(fn ($state) => date('h:i A', strtotime($state))) // Format the time as 12-hour format
                ->searchable(),
                Tables\Columns\ToggleColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->modifyQueryUsing(function (Builder $query) { 
        
                return $query->whereNotIn('type', ['register_type', 'u_sidebar_background_primary_color', 'u_sidebar_background_secondary_color','u_sidebar_background_secondary_color','u_sidebar_background_secondary_color','utheme','mtheme','logo']); 
            
            }) 
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function saving(Form $form, $record)
{
    // Convert 'days' array into a string before saving
    if ($form->getState()['days']) {
        $days = implode(', ', $form->getState()['days']);
        $record->days = $days; // Store it in the 'days' field (database column)
    }

    // If necessary, process 'value' field before saving (e.g., combining fields)
    $record->value = $form->getState()['value']; // Adjust as needed
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }    
}
