<?php

use Illuminate\Database\Seeder;

class EstablishmentInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('covid_tracer')->table('establishment_information')->insert([
            [
                'establishment_category_id' => '5',
                'owner_id' => '1',
                'establishment_identification_code' => 'EBBQ0000000000202082',
                'business_name' => 'MCDO',
                'business_permit_number' => '123',
                'address' => '123 St.',
                // 'address_id' => '2',
                'status' => '1',
            ],
            [
                'establishment_category_id' => '5',
                'owner_id' => '1',
                'establishment_identification_code' => 'EPCN0000000000202082',
                'business_name' => 'JOLLIBEE',
                'business_permit_number' => '123',
                'address' => '123 St.',
                // 'barangay_id' => '2',   
                'status' => '1',
            ],
            [
                'establishment_category_id' => '5',
                'owner_id' => 1,
                'establishment_identification_code' => 'EHNC0000000000202082',
                'business_name' => 'SME',
                'business_permit_number' => '123',
                'address' => '123 St.',
                // 'barangay_id' => '2',
                'status' => '1',
            ],
            [
                'establishment_category_id' => '5',
                'owner_id' => 1,
                'establishment_identification_code' => 'ELWO0000000000202082',
                'business_name' => '7ELEVEN',
                'business_permit_number' => '123',
                'address' => '123 St.',
                // 'barangay_id' => '2',
                'status' => '1',
            ],
        ]);
    }
}
