<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\UserResource\RelationManagers\PaymentMethodsRelationManager;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;

// use App\Filament\Resources\UserResource\Widgets\UserOverview;
// use Filament\Forms\Components\Fieldset;
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Users';

    public static function getNavigationLabel(): string
{
    return 'Users';
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->columns(2)
                    ->schema([
                        
                       // TextInput::make('team.sponsorinfo.username')->required(),
                        TextInput::make('name')->required(),
                        TextInput::make('username')->required()->readonly(),
                        TextInput::make('mobile')->required()->readonly(),
                        TextInput::make('email')->email()->required(),
                    TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => bcrypt($state)) // hash only when provided
                        ->dehydrated(fn ($state) => filled($state)) // skip updating if empty
                        ->label('New Password')
                        ->nullable(),
                        TextInput::make('mobile')->tel()->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                        // Forms\Components\Select::make('block_status')
                        // ->options([
                        //     0 => 'unBlocked',
                        //     1 => 'Blocked', 
                        // ])
                        // ->default(0)
                        // ->required(),
                    ])
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mobile')->searchable()->label('Username'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('mobile')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('team.sponsorinfo.mobile')->searchable()->label('Sponsor'),
                Tables\Columns\TextColumn::make('active_status'),
                Tables\Columns\TextColumn::make('block_status'),
            ])
            ->filters([
                Tables\Filters\Filter::make('verified')
                ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Action::make('Login')->action(function (User $user, $data ) : void {
                     
                //      Auth::guard('web')->login($user);
                //      redirect()->route('dashboard');
                // })
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
            //UsersRelationManager::class,
            PaymentMethodsRelationManager::class,
        ];
    }
    // public static function getWidgets(): array
    // {
    //     return [
    //         UserOverview::class,
    //     ];
    // }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    
}
