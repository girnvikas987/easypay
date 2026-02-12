<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;
    public function beforeSave(): void
{
    // Get the current record (this can be any model you're working with, e.g., settings, wallet, etc.)
    $record = $this->getRecord();

    // Get the form state (this is the form data)
    $formState = $this->form->getState(); // Access the form state
    
    // Get the days array from the form input
    $days = $formState['days'] ?? [];

    // Convert the days array to a comma-separated string
    if ($days) {
        // Ensure the array is not empty before converting
        $record->days = implode(', ', $days); // Save as a comma-separated string
    }

    // Save the updated record
    $record->save();
}



    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
