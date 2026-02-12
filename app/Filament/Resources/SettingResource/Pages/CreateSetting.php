<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSetting extends CreateRecord
{
    protected static string $resource = SettingResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // If 'days' is provided as an array, convert it to a string
    if (isset($data['days']) && is_array($data['days'])) {
        $data['days'] = implode(', ', $data['days']); // Convert to a string like "tuesday, thursday, friday, saturday"
    }

    // Save the record (e.g., create a new setting)
    $record = Setting::create($data);

    return $record;
    }

    public function mount(): void
    {
        parent::mount();
    
        $record = $this->getRecord();
    
        // Decode the JSON string to an array for the edit form
        if ($record && $record->days) {
            $record->days = json_decode($record->days, true); // Decode to array
        }
    }
    
}
