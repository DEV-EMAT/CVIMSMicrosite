<?php

use Illuminate\Database\Seeder;

class VaxIdCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('covid19vaccine')->table('id_categories')->insert([
            [
                'id_category_name' => 'PRC number',
                'id_category_code' => '01_PRC_number', 
                'status' => '1'
            ],
            [
                'id_category_name' => 'OSCA number',
                'id_category_code' => '02_OSCA_number', 
                'status' => '1'
            ],
            [
                'id_category_name' => 'Facility ID number',
                'id_category_code' => '03_Facility_ID_number', 
                'status' => '1'
            ],
            [
                'id_category_name' => 'PWD ID',
                'id_category_code' => '04 — PWD ID', 
                'status' => '1'
            ],
            [
                'id_category_name' => 'Other ID',
                'id_category_code' => '04_Other_ID', 
                'status' => '1'
            ]
        ]);
    }
}
