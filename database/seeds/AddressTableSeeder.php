<?php

use Illuminate\Database\Seeder;

class AddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addresses')->insert([
            'region' => 'REGION IV-A',
            'region_id' => '4A',
            'barangay' => 'BUKAL',
            'barangay_id' => 8,
            'city' => 'MAJAYJAY',
            'province' => 'LAGUNA',
            'status' => '1'
        ]);
    }
}


// INSERT INTO `addresses` (`id`, `region`, `region_id`, `barangay`, `barangay_id`, `city`, `province`, `status`, `created_at`, `updated_at`) VALUES
// (3, 'REGION IV-A', '4A', 'BUKAL', '8', 'MAJAYJAY', 'LAGUNA', '1', '2020-08-26 07:20:41', '2020-08-26 07:34:44'),
// (8, 'REGION XIII', '13', 'BAILAN', '2', 'SANTA MONICA (SAPAO)', 'SURIGAO DEL NORTE', '1', '2020-08-26 09:03:42', '2020-08-26 09:03:42'),
// (9, 'REGION I', '01', 'SANTA TERESA', '17', 'TUBAO', 'LA UNION', '1', '2020-08-26 09:05:00', '2020-08-26 09:05:00'),
// (11, 'REGION XIII', '13', 'ROMA', '18', 'BASILISA (RIZAL)', 'DINAGAT ISLANDS', '1', '2020-08-26 09:15:56', '2020-08-26 09:15:56'),
// (14, 'REGION XI', '11', 'TAMIA', '15', 'COMPOSTELA', 'COMPOSTELA VALLEY', '1', '2020-08-26 09:23:22', '2020-08-26 09:23:22'),
// (15, 'REGION I', '01', 'TAMBAC', '14', 'DASOL', 'PANGASINAN', '1', '2020-08-26 09:23:52', '2020-08-26 09:23:52'),
// (16, 'REGION I', '01', 'NARVACAN', '16', 'SANTO TOMAS', 'LA UNION', '1', '2020-08-27 00:38:23', '2020-08-27 00:38:23'),
// (17, 'REGION IV-A', '4A', 'BUCAL', '17', 'SILANG', 'CAVITE', '1', '2020-08-27 00:40:20', '2020-08-27 00:56:38'),
// (31, 'REGION III', '03', 'SANTO DOMINGO 2ND', '17', 'CAPAS', 'TARLAC', '1', '2020-08-27 01:16:57', '2020-08-27 01:18:39'),
// (53, 'REGION III', '03', 'CALINGCUAN', '20', 'CITY OF TARLAC', 'TARLAC', '1', '2020-08-27 01:54:23', '2020-08-27 01:55:15'),
// (54, 'REGION IV-A', '4A', 'GALALAN', '2', 'PANGIL', 'LAGUNA', '1', '2020-08-27 01:56:00', '2020-08-27 01:56:00');