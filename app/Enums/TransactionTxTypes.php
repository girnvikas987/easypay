<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
enum TransactionTxTypes: string implements HasLabel,HasColor, HasIcon
{
    case Income = 'income';
    case AddFUND = 'add_fund';
    case Bouns = 'bouns';
    case Topup = 'topup';
    case GoldTopup = 'gold_topup';
    case LoanTopup = 'loan_topup';
    case EbikeTopup = 'ebike_topup';
    case TourTopup = 'tour_topup';
    case EliteTopup = 'elite_topup';
    case FlyTopup = 'fly_topup';
    case RechargeTopup = 'recharge_topup';
    case Transfer = 'transfer';
    case Convert = 'convert';
    case RechargePack = 'recharge_pack';
    case BuyBtc = 'buy_btc';
    case SellBtc = 'sell_btc';
    case BuyGold = 'buy_gold';
    case SellGold = 'sell_gold';
    case Withdraw = 'withdraw';
    case Recharge = 'recharge';
    case Commitee = 'commitee';
    case SavingFund = 'saving_fund';
    
   
    
    public function getLabel(): ?string
    {
        return $this->name;
        
        // or
    
        return match ($this) {
            self::Income => 'income',
            self::AddFUND => 'add_fund',
            self::Bouns => 'bouns',            
            self::Topup => 'topup',            
            self::LoanTopup => 'loan_topup',            
            self::EbikeTopup => 'ebike_topup',            
            self::TourTopup => 'tour_topup',            
            self::EliteTopup => 'elite_topup',            
            self::FlyTopup => 'fly_topup',            
            self::RechargeTopup => 'recharge_topup',            
            self::Transfer => 'transfer',            
            self::Convert => 'convert',            
            self::RechargePack => 'recharge_pack',            
            self::BuyBtc => 'buy_btc',            
            self::SellBtc => 'sell_btc',            
            self::BuyGold => 'buy_gold',            
            self::SellGold => 'sell_gold',            
            self::GoldTopup => 'gold_topup',            
            self::Withdraw => 'withdraw',            
            self::Recharge => 'recharge',            
            self::Commitee => 'commitee',            
            self::SavingFund => 'saving_fund',            
        };
    }
    
    public function getColor(): string | array | null
    {
        return match ($this) {
            
            self::Income => 'gray',
            self::AddFUND => 'success',
            self::Bouns => 'success', 
            self::Topup => 'topup', 
            self::LoanTopup => 'loan_topup', 
            self::EbikeTopup => 'ebike_topup', 
            self::TourTopup => 'tour_topup', 
            self::EliteTopup => 'elite_topup', 
            self::RechargeTopup => 'recharge_topup', 
            self::Transfer => 'transfer', 
            self::Convert => 'transfer', 
            self::RechargePack => 'recharge_pack', 
            self::BuyBtc => 'buy_btc', 
            self::SellBtc => 'sell_btc', 
             self::BuyGold => 'buy_gold',            
            self::SellGold => 'sell_gold',  
            self::GoldTopup => 'gold_topup',  
            self::Withdraw => 'withdraw',  
            self::Recharge => 'recharge',  
            self::FlyTopup => 'fly_topup',  
            self::Commitee => 'commitee',  
            self::SavingFund => 'saving_fund',  
            
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Income => 'heroicon-m-check',
            self::AddFUND => 'heroicon-m-check',
            self::Bouns => 'heroicon-m-check',
            self::Topup => 'heroicon-m-check',
            self::LoanTopup => 'heroicon-m-check',
            self::EbikeTopup => 'heroicon-m-check',
            self::RechargeTopup => 'heroicon-m-check',
            self::Transfer => 'heroicon-m-check',
            self::Convert => 'heroicon-m-check',
            self::RechargePack => 'heroicon-m-check',
            self::BuyBtc => 'heroicon-m-check',
            self::SellBtc => 'heroicon-m-check',
            self::BuyGold => 'heroicon-m-check',
            self::SellGold => 'heroicon-m-check',
            self::GoldTopup => 'heroicon-m-check',
            self::Withdraw => 'heroicon-m-check',
            self::Recharge => 'heroicon-m-check',
            self::EliteTopup => 'heroicon-m-check',
            self::FlyTopup => 'heroicon-m-check',
            self::Commitee => 'heroicon-m-check',
            self::SavingFund => 'heroicon-m-check',
            
            
        };
    }
}