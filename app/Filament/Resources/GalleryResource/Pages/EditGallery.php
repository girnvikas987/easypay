<?php

namespace App\Filament\Resources\GalleryResource\Pages;

use App\Filament\Resources\GalleryResource;
use App\Helper\Distribute;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGallery extends EditRecord
{
    protected static string $resource = GalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $transaction = (object) [
            'tx_type' => $this->record->title,   // Assuming `title` is the transaction type
            'remark' => $this->record->description,  // Assuming `description` is the remark
        ];

        // Call your helper function to notify all users
        Distribute::notify($transaction);

        // You can also trigger a notification for success
        //$this->notify('success', 'Notification sent successfully!');
    }
}
