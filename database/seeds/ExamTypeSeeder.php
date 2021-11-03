<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ExamTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('comprehensive')->table('exam_types')->insert([
            [
                'type' => 'MULTIPLE CHOICES',
                'description' => '1',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'TRUE OR FALSE',
                'description' => '1',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
