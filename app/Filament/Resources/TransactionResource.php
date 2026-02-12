<?php

namespace App\Filament\Resources;

use App\Enums\TransactionStatus;
use App\Enums\TransactionTxTypes;
use App\Enums\TransactionTypes;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Transactions';
    public static function getNavigationLabel(): string
    {
        return 'History';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->columns(2)
                    ->schema([
                         
                        Select::make('user_id')
                        ->relationship(name: 'user', titleAttribute: 'username')
                        ->searchable(['username']),
                        // TextInput::make('username')->required(),
                        TextInput::make('amount')->required(),                        
                        Select::make('tx_type')->options(TransactionTxTypes::class),
                        Select::make('wallet')
                        ->relationship(name: 'wallet_type', titleAttribute: 'name'),
                        Select::make('status')->options(TransactionStatus::class),
                        Select::make('type')->options(TransactionTypes::class)->native(false)->required(),
                        
                        Checkbox::make('use_wallet'),
                        
                       
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.mobile')->searchable()->label('Username'),
                Tables\Columns\TextColumn::make('tx_user_details.mobile')->searchable()->label('To'), 
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('tx_type')->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('income')->searchable(),
                Tables\Columns\TextColumn::make('level')->searchable(), 
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                // SelectColumn::make('status')->options(TransactionStatus::class)
            ])
            ->filters([
                Tables\Filters\Filter::make('Success')
                ->query(fn (Builder $query): Builder => $query->where('status',1)),
                Tables\Filters\Filter::make('Only Incomes')
                ->query(fn (Builder $query): Builder => $query->where('tx_type',"income")),

                Tables\Filters\Filter::make('Only Incomes')
                ->query(fn (Builder $query): Builder => $query->where('tx_type', 'income')),
                SelectFilter::make('income')
                ->options([
                    'direct' => 'Referral', 
                    'level' => 'Team level', 
                    'roi' => 'Sign-up Bouns', 
                    'salary' => 'Salary Income', 
                ]),
                SelectFilter::make('tx_type')
                ->options([
                    'add_fund' => 'Add Fund',
                    'income' => 'Income',
                    'topup' => 'Topup',
                    'withdraw' => 'Withdrawal',
                ])
                ->label('Transaction Type'),
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
            'index' => Pages\ListTransactions::route('/'),
            // 'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }    
}
