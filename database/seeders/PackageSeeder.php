<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Package::insert([
        [
            'name' => 'Prime', 
            'slug' => 'prime', 
            'type' => 'manual',
            'min' => '599',
            'max' => '599',
            'no_of_time' => '1',
            'amount' => '599',
            'pre_reqired' => '1',
            
         ],
        [
            'name' => 'Super Prime', 
            'slug' => 'super_prime', 
            'type' => 'manual',
            'min' => '1199',
            'max' => '1199',
            'no_of_time' => '1',
            'amount' => '1199',
            'pre_reqired' => '1',
            
         ],
    
    ]);
    }
}
