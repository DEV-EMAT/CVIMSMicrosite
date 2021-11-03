<?php

use Illuminate\Database\Seeder;

class VaxCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::connection('covid19vaccine')->table('categories')->insert([
            [
                'category_format' => '01_Health_Care_Worker',
                'category_name' => 'Health Care Worker', 
                'status' => '1'
            ],
            [
                'category_format' => '02_Senior_Citizen',
                'category_name' => 'Senior Citizen', 
                'status' => '1'
            ],
            [
                'category_format' => '03_Indigent ',
                'category_name' => 'Indigent', 
                'status' => '1'
            ],
            [
                'category_format' => '04_Uniformed_Personnel',
                'category_name' => 'Uniformed Personnel', 
                'status' => '1'
            ],
            [
                'category_format' => '05_Essential_Worker',
                'category_name' => 'Essential Worker', 
                'status' => '1'
            ],
            [
                'category_format' => '06_Other',
                'category_name' => ' Other', 
                'status' => '1'
            ]
        ]);
           
    }
}
