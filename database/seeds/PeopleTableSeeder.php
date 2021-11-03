<?php

use Illuminate\Database\Seeder;

class PeopleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('people')->insert([
            //SUPER ADMIN GENERAL -1
        [
            'first_name' => '--',
            'middle_name' => '--',
            'last_name' => 'SUPER-ADMIN-GENERAL',
            'affiliation' => '',
            'gender' => '1',
            'date_of_birth' => '1999-06-26',
            'address' =>'blk 123 lot456',
            'address_id' => 1,
            'civil_status' => '1',
            'telephone_number' => '092-123-411',
            'religion' => 'catholic',
            'image' => 'ecabs/profiles/ecab-admin.png',
        ],
        //SUPER ADMIN ECABS -2
        [
            'first_name' => '--',
            'middle_name' => '--',
            'last_name' => 'ECABS-SUPER-ADMIN',
            'affiliation' => '',
            'gender' => '1',
            'date_of_birth' => '1999-06-26',
            'address' =>'blk 123 lot456',
            'address_id' => 1,
            'civil_status' => '1',
            'telephone_number' => '092-123-412',
            'religion' => 'catholic',
            'image' => 'ecabs/profiles/ecab-admin.png',
        ],
        //COVID TRACER SUPER ADMIN -3
        [
            'first_name' => '--',
            'middle_name' => '--',
            'last_name' => 'COVID-TRACER-SUPER-ADMIN',
            'affiliation' => '',
            'gender' => '1',
            'date_of_birth' => '1999-06-26',
            'address' =>'blk 123 lot456',
            'address_id' => 1,
            'civil_status' => '1',
            'telephone_number' => '092-123-413',
            'religion' => 'catholic',
            'image' => 'ecabs/profiles/ecab-admin.png',
        ],
        //CSMS-SUPER-ADMIN -4
        [
            'first_name' => '--',
            'middle_name' => '--',
            'last_name' => 'CSMS-SUPER-ADMIN',
            'affiliation' => '',
            'gender' => '1',
            'date_of_birth' => '1999-06-26',
            'address' =>'blk 123 lot456',
            'address_id' => 1,
            'civil_status' => '1',
            'telephone_number' => '092-123-413',
            'religion' => 'catholic',
            'image' => 'ecabs/profiles/ecab-admin.png',
        ],
        ]);
    }
}
