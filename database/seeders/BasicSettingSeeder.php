<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Autopool;
use App\Models\DailyIncome;
use App\Models\Income;
use App\Models\Investment;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BasicSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $admin1 = Admin::create([
            "name" => "Admin",
            "email" => "admin@gmail.com",
            "password" => Hash::make("aaaaaaaa"),
            "mobile" => "9876543210",
            "status" => "1",
        ]);

        // Create second admin (SuperAdmin)
        $admin2 = Admin::create([
            "name" => "SuperAdmin",
            "email" => "pay30@gmail.com",
            "password" => Hash::make("pay30@3030"),
            "mobile" => "9999999999",
            "status" => "1",
        ]);

        // Create roles if not exists
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'admin']);
        $superAdminRole = Role::firstOrCreate(['name' => 'SuperAdmin', 'guard_name' => 'admin']);

        // Assign roles
        $admin1->assignRole($adminRole);
        $admin2->assignRole($superAdminRole);
        
        User::create([
            'name' => env('APP_NAME'),
            
             
            'email' => 'admin@admin.com',
            'password' => Hash::make('aaaaaaaa'),
            'username' => env('APP_NAME'),
            'mobile' => '9876543210',
            'transaction_pin' => '1234',
            'active_status' => '1',
        ]);

        Team::create([
            'user_id' => '1',
            'sponsor' => '0',
            'active_status'=> '1',
            'gen' => []
        ]);
        Wallet::create([
            'user_id'=> 1,
        ]);
        Income::create([
            'user_id'=> 1,
        ]);
        DailyIncome::create([
            'user_id'=> 1,
        ]);
        Autopool::insert([
            'user_id'    => 1,
            'parent_id'  => 1,
            'pool'       => 'default',
            'pool_num'   => 1,
            'created_at' => '2025-06-04 12:40:05',
            'updated_at' => '2025-06-04 12:40:05',
        ]);


        Investment::insert([
            [
                'user_id' => 1, 
                'tx_user' => 1,
                'package_id' => 1,
                'amount' => 599,
                'status' => '1', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'tx_user' => 1,
                'package_id' => 2,
                'amount' => 1199,
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
           
        ]);
        
    }
}
