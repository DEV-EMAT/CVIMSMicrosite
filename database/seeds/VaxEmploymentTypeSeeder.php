<?php

use Illuminate\Database\Seeder;

class VaxEmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::connection('covid19vaccine')->table('employment_statuses')->insert([
            [
                'employment_type_format' => '01_Government_Employed',
                'employment_type' => 'Government Employed', 
                'status' => '1'
            ],
            [
                'employment_type_format' => '02_Private_Employed',
                'employment_type' => 'Private Employed', 
                'status' => '1'
            ],
            [
                'employment_type_format' => '03_Self_employed',
                'employment_type' => 'Self-employed', 
                'status' => '1'
            ],
            [
                'employment_type_format' => '04_Private_practitioner',
                'employment_type' => 'Private Practitioner', 
                'status' => '1'
            ],
            [
                'employment_type_format' => '05_Others',
                'employment_type' => 'Others', 
                'status' => '1'
            ]
        ]);
    }
}
