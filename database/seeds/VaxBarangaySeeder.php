<?php

use Illuminate\Database\Seeder;

class VaxBarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('covid19vaccine')->table('barangays')->insert([
            [
                'barangay' => 'BACLARAN',
                'real_name' => 'BACLARAN',
                'DOH_brgy_id' => '_43404001_BACLARAN',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'BACLARAN (MABUHAY)',
                'real_name' => 'BACLARAN',
                'DOH_brgy_id' => '_43404001_BACLARAN',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'BANAYBANAY',
                'real_name' => 'BANAYBANAY',
                'DOH_brgy_id' => '_43404002_BANAYBANAY',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'BANAYBANAY (SOUTHVILLE)',
                'real_name' => 'BANAYBANAY',
                'DOH_brgy_id' => '_43404002_BANAYBANAY',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'BANLIC',
                'real_name' => 'BANLIC',
                'DOH_brgy_id' => '_43404003_BANLIC',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'BUTONG',
                'real_name' => 'BUTONG',
                'DOH_brgy_id' => '_43404004_BUTONG',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'BIGAA',
                'real_name' => 'BIGAA',
                'DOH_brgy_id' => '_43404005_BIGAA',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'CASILE',
                'real_name' => 'CASILE',
                'DOH_brgy_id' => '_43404006_CASILE',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'GULOD',
                'real_name' => 'GULOD',
                'DOH_brgy_id' => '_43404007_GULOD',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'MAMATID',
                'real_name' => 'MAMATID',
                'DOH_brgy_id' => '_43404009_MAMATID',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'MAMATID (MABUHAY)',
                'real_name' => 'MAMATID',
                'DOH_brgy_id' => '_43404009_MAMATID',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'MARINIG',
                'real_name' => 'MARINIG',
                'DOH_brgy_id' => '_43404010_MARINIG',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'MARINIG (NORTH)',
                'real_name' => 'MARINIG',
                'DOH_brgy_id' => '_43404010_MARINIG',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'MARINIG (SOUTH)',
                'real_name' => 'MARINIG',
                'DOH_brgy_id' => '_43404010_MARINIG',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'MARINIG (SOUTHVILLE)',
                'real_name' => 'MARINIG',
                'DOH_brgy_id' => '_43404010_MARINIG',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'NIUGAN',
                'real_name' => 'NIUGAN',
                'DOH_brgy_id' => '_43404011_NIUGAN',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'PITTLAND',
                'real_name' => 'PITTLAND',
                'DOH_brgy_id' => '_43404012_PITTLAND',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'PULO',
                'real_name' => 'PULO',
                'DOH_brgy_id' => '_43404013_PULO',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'SALA',
                'real_name' => 'SALA',
                'DOH_brgy_id' => '_43404014_SALA',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'SAN_ISIDRO',
                'real_name' => 'SAN_ISIDRO',
                'DOH_brgy_id' => '_43404015_SAN_ISIDRO',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'DIEZMO',
                'real_name' => 'DIEZMO',
                'DOH_brgy_id' => '_43404016_DIEZMO',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'BARANGAY_UNO_(POB.)',
                'real_name' => 'BARANGAY_UNO_(POB.)',
                'DOH_brgy_id' => '_43404017_BARANGAY_UNO_(POB.)',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'BARANGAY_DOS_(POB.)',
                'real_name' => 'BARANGAY_DOS_(POB.)',
                'DOH_brgy_id' => '_43404018_BARANGAY_DOS_(POB.)',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
            [
                'barangay' => 'BARANGAY_TRES_(POB.)',
                'real_name' => 'BARANGAY_TRES_(POB.)',
                'DOH_brgy_id' => '_43404019_BARANGAY_TRES_(POB.)',
                'city' => '_43404_CABUYAO_CITY',
                'province' => '_0434_LAGUNA',
                'zipcode' => '4025',
                'status' => '1'
            ],
        ]);
    }
}
