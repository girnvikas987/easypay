<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
enum TransactionStatus: string implements HasLabel,HasColor, HasIcon
{
    case Success = '1';
    case Pending = '0';
    case Fail = '2';
    case Processing = '3';
    case Rejected = '4';
   
    
    public function getLabel(): ?string
    {
        return $this->name;
        
        // or
    
        return match ($this) {
            self::Success => '1',
            self::Pending => '0',
            self::Fail => '2',
            self::Processing => '3',
            self::Rejected => '4',
            
            
        };
    }
    public function getColor(): string | array | null
    {
        return match ($this) {
            
            self::Success => 'success',
            self::Pending => 'gray',
            self::Fail => 'danger',
            self::Processing => 'warning',
            self::Rejected => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Success => 'heroicon-m-check',
            self::Pending => 'heroicon-s-cube-transparent',            
            self::Fail => 'heroicon-m-x-mark',
            self::Processing => 'heroicon-m-pencil',
            self::Rejected => 'heroicon-m-x-mark',
        };
    }
}