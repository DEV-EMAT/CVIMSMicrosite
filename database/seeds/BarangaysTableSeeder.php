<?php

use Illuminate\Database\Seeder;

class BarangaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('barangays')->insert([
            [
                'id'=>'1',
                'barangay' => 'UNKNOWN',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'2',
                'barangay' => 'BACLARAN',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'3',
                'barangay' => 'BANAYBANAY',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'4',
                'barangay' => 'BANLIC',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'5',
                'barangay' => 'BIGAA',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'6',
                'barangay' => 'BUTONG',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'7',
                'barangay' => 'CASILE',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'8',
                'barangay' => 'DIEZMO',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'9',
                'barangay' => 'GULOD',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'10',
                'barangay' => 'MAMATID',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'11',
                'barangay' => 'MARINIG',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'12',
                'barangay' => 'NIUGAN',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'13',
                'barangay' => 'PITTLAND',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'14',
                'barangay' => 'BARANGAY DOS (POB.)',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'15',
                'barangay' => 'BARANGAY TRES (POB.)',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'16',
                'barangay' => 'BARANGAY UNO (POB.)',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'17',
                'barangay' => 'PULO',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'18',
                'barangay' => 'SALA',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ],
            [
                'id'=>'19',
                'barangay' => 'SAN ISIDRO',
                'city' => 'CABUYAO',
                'province' => 'LAGUNA',
                'zipcode' => '4025',
                'status' =>'1'       
            ]
        ]);
    }
}
