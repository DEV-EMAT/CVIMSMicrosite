<?php

use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('platforms')->insert([
            [
                'platform_type' => 'mobile',
                'status' => 1,
            ],
            [
                'platform_type' => 'web',
                'status' => 1,
            ],
            [
                'platform_type' => 'both',
                'status' => 1,
            ]
        ]);
    }
}
