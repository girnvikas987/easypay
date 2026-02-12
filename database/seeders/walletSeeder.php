<?php

namespace Database\Seeders;

use App\Models\WalletType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;

class walletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $wallets =[
                [
                    'name' => 'Main Wallet',
                    'slug' => 'main_wallet',
                    'type' => 'wallet',
                    'status' => true,
                ],
                [
                    'name' => 'Fund Wallet',
                    'slug' => 'fund_wallet',
                    'type' => 'wallet',
                    'status' => true,
                ],
                [
                    'name' => 'Bouns Wallet',
                    'slug' => 'bouns_wallet',
                    'type' => 'wallet',
                    'status' => true,
                ],
                 
            ]; 
         
            WalletType::insert($wallets);
            
            foreach ($wallets as $wallet) {
                Schema::table('wallets', function (Blueprint $table) use ($wallet) {
                    $table->double($wallet['slug'], 15, 8)->nullable()->default(0);
                    
                });
            }
            
    }
}
