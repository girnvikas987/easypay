<?php

namespace App\Filament\Resources\GalleryResource\Pages;

use App\Filament\Resources\GalleryResource;
use App\Helper\Distribute;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Gallery;
use Google_Client;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Storage;

class CreateGallery extends CreateRecord
{
    protected static string $resource = GalleryResource::class;
    
    protected function afterCreate(): void
    {
     

        // Call your helper function to notify all users
        $credentialsFilePath = Storage::path('json/google-services.json');
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        $access_token = $token['access_token'];

         
        $transaction = (object) [
            'tx_type' => $this->record->title,   // Assuming `title` is the transaction type
            'remark' => $this->record->description,  // Assuming `description` is the remark
            'access_token' =>$access_token
        ];
        
        Distribute::notify($transaction);

        // You can also trigger a notification for success
       // $this->notify('success', 'Notification sent successfully!');
    }
}
