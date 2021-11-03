<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        [
            'email' => 'admin@admin.com',
            'person_id' => 1,
            'email_verified_at' => Carbon::now(),
            'contact_number' => '+639090000001',
            'password' => bcrypt('admin123'),
            'account_status' => 1,
            'device_identifier' => Hash::make('web_account'),
            'mac_address' => exec('getmac')
        ],
        [
            'email' => 'ecabs@admin.com',
            'person_id' => 2,
            'email_verified_at' => Carbon::now(),
            'contact_number' => '+639090000002',
            'password' => bcrypt('admin123'),
            'account_status' => 1,
            'device_identifier' => Hash::make('web_account'),
            'mac_address' => exec('getmac')
        ],
        [
            'email' => 'covidtracer@admin.com',
            'person_id' => 3,
            'email_verified_at' => Carbon::now(),
            'contact_number' => '+639090000003',
            'password' => bcrypt('admin123'),
            'account_status' => 1,
            'device_identifier' => Hash::make('web_account'),
            'mac_address' => exec('getmac')
        ],
        [
            'email' => 'csms@admin.com',
            'person_id' => 4,
            'email_verified_at' => Carbon::now(),
            'contact_number' => '+639090000004',
            'password' => bcrypt('admin123'),
            'account_status' => 1,
            'device_identifier' => Hash::make('web_account'),
            'mac_address' => exec('getmac')
        ],
        ]);  
    }
}
