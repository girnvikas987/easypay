<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserFundRequestResource\Pages;
use App\Filament\Resources\UserFundRequestResource\RelationManagers;
use App\Models\UserFundRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\ImageColumn;
 
use Illuminate\Support\Facades\Storage as FacadesStorage;

class UserFundRequestResource extends Resource
{
    protected static ?string $model = UserFundRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-rupee';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Fund Request';

    public static function getNavigationLabel(): string
{
    return 'Fund Request';
}
public static function getNavigationBadge(): ?string
{
    return static::getModel()::query()->where('status','0')->count();
}

public static function getNavigationBadgeColor(): string|array|null
{
     return  'danger';
}

 



    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make()
            ->columns(2)
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'mobile')
                    ->required(),
                // Forms\Components\Select::make('fund_request_method_id')
                //     ->relationship('fund_request_method', 'name')
                //     ->required(),
                // Forms\Components\Select::make('fund_request_method_option_id')
                //     ->relationship('fund_request_method_option', 'name')
                //     ->required(),
                
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0.00000000),

                    // Forms\Components\Select::make('transaction_type')
                    // ->label('Transaction Type')
                    // ->options([
                    //     'credit' => 'Credit',
                    //     'debit' => 'Debit',
                    // ])
                    // ->required()
                    // ->default('credit')
                    // ->reactive()
                    // ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    //     $amount = $get('amount') ?: 0; // get current amount
                        
                    //     if ($state == 'debit') {
                    //         // Decrease the amount for debit
                    //         $set('amount', abs($amount));
                    //     } else {
                    //         // Keep the amount positive for credit
                    //         $set('amount', abs($amount));
                    //     }
                    // }),
                Forms\Components\TextInput::make('utr_number')
                    ->required(),
                    Forms\Components\Select::make('status')
                    ->options([
                        0 => 'Pending',
                        1 => 'Approved',
                        2 => 'Cancelled',
                        4 => 'Retrieve',
                    ])
                    ->default(0)
                    ->required(),

                    
                    Forms\Components\Select::make('wallet')
                    ->options([
                        'fund_wallet' => 'Fund Wallet',
                        // 'main_wallet' => 'Main Wallet', 
                    ])
                    ->default(0)
                    ->required(),

                    
    
            ]),
            
            ]);
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.mobile') 
                    ->label('Mobile')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('fund_request_method.name')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('utr_number') 
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->copyable()
                    ->sortable(), 
              Tables\Columns\TextColumn::make('screenshot')
                ->label('Screenshot')
                ->formatStateUsing(fn ($state) => 'View Screenshot')
                ->url(fn ($record) => \Illuminate\Support\Facades\Storage::url($record->screenshot))
                ->openUrlInNewTab(),

                
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
                // Tables\Filters\SelectFilter::make('transaction_type')
                //     ->label('Transaction Type')
                //     ->options([
                //         'credit' => 'Credit',
                //         'debit'  => 'Debit',
                //     ])
                //     ->default('credit') // No default filter, show both by default
                //     ->query(function ($query, $data) {
                //         if ($data) {
                //             $query->where('transaction_type', $data);
                //         }
                //     }),
            ])
 
            ->actions([
                
                Tables\Actions\EditAction::make()
                ->disabled(function ($record) {
                      
                    return $record->status->value == 1; // Disable if status is 'Approved' (1)
                }), 

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
            'index' => Pages\ListUserFundRequests::route('/'),
            'create' => Pages\CreateUserFundRequest::route('/create'),
            'edit' => Pages\EditUserFundRequest::route('/{record}/edit'),
        ];
    }    
}
