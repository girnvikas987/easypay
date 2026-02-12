<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalResource\Pages;
use App\Filament\Resources\WithdrawalResource\RelationManagers;
use App\Models\Withdrawal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\TransactionStatus;
 

class WithdrawalResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Other';
    
    public static function form(Form $form): Form
    {
        return $form
        
        

            ->schema([
                

                Select::make('status')
                        ->options([
                            '0' => 'Pending',
                            '1' => 'Approved',                        
                            '4' => 'Rejected',                        
                                              
                        ]),
                Forms\Components\TextInput::make('amount')
                        ->numeric()
                        ->default(0.00000000)->readonly(),
                Forms\Components\Textarea::make('reason')
                    ->rows(10)
                    ->cols(20),
            ]);
    }
    
     
     
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.mobile')->searchable()->label('Username'),
                Tables\Columns\TextColumn::make('user.name')->searchable(),
                Tables\Columns\TextColumn::make('user.bankDetail.branch_name')->searchable()->label('Branch Name'),
                Tables\Columns\TextColumn::make('user.bankDetail.holder_name')->searchable()->label('Holder Name'),
                Tables\Columns\TextColumn::make('user.bankDetail.account')->searchable()->label('Account'),
                Tables\Columns\TextColumn::make('user.bankDetail.ifsc_code')->searchable()->label('Ifsc Code'),
                Tables\Columns\TextColumn::make('amount')->label('Payable Amount'),
                Tables\Columns\TextColumn::make('tx_charge')->label('Tx Charge'),
                Tables\Columns\TextColumn::make('tds_charge')->label('TDS Charge'),
                Tables\Columns\TextColumn::make('reason')->label('Reject Reason'), 
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false),



            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->disabled(function ($record) {
                      
                    return in_array($record->status->value, [1, 4]);// Disable if status is 'Approved' (1)
                }), 

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
            'index' => Pages\ListWithdrawals::route('/'),
            'create' => Pages\CreateWithdrawal::route('/create'),
            'edit' => Pages\EditWithdrawal::route('/{record}/edit'),
        ];
    }    
}
