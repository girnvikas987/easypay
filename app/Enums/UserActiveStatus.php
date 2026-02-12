<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
enum UserActiveStatus: string implements HasLabel,HasColor, HasIcon
{
    case Active = '1';
    case Inactive = '0';
   
    
    public function getLabel(): ?string
    {
        return $this->name;
        
        // or
    
        return match ($this) {
            self::Active => '1',
            self::Inactive => '0',
            
        };
    }
    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Active => 'success',
            self::Inactive => 'warning',
            
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            
            self::Active => 'heroicon-m-check',
            self::Inactive => 'heroicon-m-x-mark',
        };
    }
}