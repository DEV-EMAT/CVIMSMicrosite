<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('maintenances')->insert([
            [
                'description' => 'ECABS-PRE-REGISTRATION',
                'status' => 1,
                'platform_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'description' => 'ISKOCAB-PRE-REGISTRATION',
                'status' => 1,
                'platform_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
