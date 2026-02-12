<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RechargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $now = now();
        Provider::insert([
         [
            'provider_id' => '1',
            'provider_name' => 'AIRTEL',
            'service_id' => '1',
            'service_name' => 'Mobile',
            'service_type' => null,
            'help_line' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'provider_id' => '4',
            'provider_name' => 'BSNL Prepaid',
            'service_id' => '1',
            'service_name' => 'Mobile',
            'service_type' => null,
            'help_line' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'provider_id' => '6',
            'provider_name' => 'Jio Prepaid',
            'service_id' => '1',
            'service_name' => 'Mobile',
            'service_type' => null,
            'help_line' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'provider_id' => '17',
            'provider_name' => 'MTNL Prepaid',
            'service_id' => '1',
            'service_name' => 'Mobile',
            'service_type' => null,
            'help_line' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'provider_id' => '2',
            'provider_name' => 'VI Prepaid',
            'service_id' => '1',
            'service_name' => 'Mobile',
            'service_type' => null,
            'help_line' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'provider_id' => '3',
            'provider_name' => 'Airtel Postpaid (Fetch and Pay)',
            'service_id' => '3',
            'service_name' => 'Mobile Postpaid',
            'service_type' => null,
            'help_line' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'provider_id' => '16',
            'provider_name' => 'BSNL Mobile Postpaid',
            'service_id' => '3',
            'service_name' => 'Mobile Postpaid',
            'service_type' => null,
            'help_line' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'provider_id' => '13',
            'provider_name' => 'Jio Postpaid (Fetch and Pay)',
            'service_id' => '3',
            'service_name' => 'Mobile Postpaid',
            'service_type' => null,
            'help_line' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'provider_id' => '14',
            'provider_name' => 'MTNL Delhi Dolphin',
            'service_id' => '3',
            'service_name' => 'Mobile Postpaid',
            'service_type' => null,
            'help_line' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'provider_id' => '20',
            'provider_name' => 'MTNL Mumbai Dolphin',
            'service_id' => '3',
            'service_name' => 'Mobile Postpaid',
            'service_type' => null,
            'help_line' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'provider_id' => '18',
            'provider_name' => 'Tata TeleServices PostPaid',
            'service_id' => '3',
            'service_name' => 'Mobile Postpaid',
            'service_type' => null,
            'help_line' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'provider_id' => '15',
            'provider_name' => 'Vi Postpaid (Fetch and Pay)',
            'service_id' => '3',
            'service_name' => 'Mobile Postpaid',
            'service_type' => null,
            'help_line' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ],
    ]);
    }
}
