<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EducationalAttainmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::connection('iskocab')->table('educational_attainments')->insert([
            [
                'title' => 'COLLEGE',
                'description' => 'COLLEGE',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'MASTERAL',
                'description' => 'MASTERAL',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
