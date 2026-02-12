<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
enum ProductStockStatus: string implements HasLabel,HasColor, HasIcon
{
    case InStock = 'in_stock';
    case OutOfStock = 'out_of_stock';


    public function getLabel(): ?string
    {
        return $this->name;

        // or

        return match ($this) {
            self::InStock => 'in_stock',
            self::OutOfStock => 'out_of_stock',
        };
    }
    public function getColor(): string | array | null
    {
        return match ($this) {
            self::InStock => 'success',
            self::OutOfStock => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::InStock => 'heroicon-m-check',
            self::OutOfStock => 'heroicon-m-x-mark',

        };
    }
}
