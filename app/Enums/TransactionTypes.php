<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
enum TransactionTypes: string implements HasLabel,HasColor, HasIcon
{
    case CREDIT = 'credit';
    case DEBIT = 'debit';
    
    
   
    
    public function getLabel(): ?string
    {
        return $this->name;
        
        // or
    
        return match ($this) {
            self::CREDIT => 'credit',
            self::DEBIT => 'debit',
                     
        };
    }
    public function getColor(): string | array | null
    {
        return match ($this) {
            
            self::CREDIT => 'success',
            self::DEBIT => 'danger',
            
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CREDIT => 'heroicon-m-check',
            self::DEBIT => 'heroicon-m-x-mark',
            
        };
    }
}