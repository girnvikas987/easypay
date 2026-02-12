<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
enum OrderStatus: string implements HasLabel,HasColor, HasIcon
{
    case Ordered = 'ordered';
    case Cancelled = 'cancelled';
    case Delivered = 'delivered';
    case Processed = 'processed';
    case Completed = 'paid';
    case Failed = 'fail';
    case Refunded = 'refunded';


    public function getLabel(): ?string
    {
        return $this->name;

        // or

        return match ($this) {
            self::Ordered => 'ordered',
            self::Cancelled => 'cancelled',
            self::Delivered => 'delivered',
            self::Processed => 'processed',
            self::Completed => 'paid',
            self::Failed => 'fail',
            self::Refunded => 'refunded',
        };
    }
    public function getColor(): string | array | null
    {
        return match ($this) {

            self::Ordered => 'gray',
            self::Cancelled => 'danger',
            self::Delivered => 'info',
            self::Processed => 'gray',
            self::Completed => 'success',
            self::Failed => 'danger',
            self::Refunded => 'primary',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Ordered => 'heroicon-m-pencil',
            self::Cancelled => 'heroicon-m-check',
            self::Delivered => 'heroicon-m-check',
            self::Processed => 'heroicon-m-pencil',
            self::Completed => 'heroicon-m-check',
            self::Failed => 'heroicon-m-check',
            self::Refunded => 'heroicon-m-check',

        };
    }
}
