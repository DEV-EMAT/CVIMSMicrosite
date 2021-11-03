<?php

use Illuminate\Database\Seeder;

class VaxProfessionsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::connection('covid19vaccine')->table('professions')->insert([
            [
                'profession_format' => '01_Dental_Hygienist',
                'profession_name' => 'Dental Hygienist', 
                'status' => '1'
            ],
            [
                'profession_format' => '02_Dental_Technologist',
                'profession_name' => 'Dental Technologist', 
                'status' => '1'
            ],
            [
                'profession_format' => '03_Dentist',
                'profession_name' => 'Dentist', 
                'status' => '1'
            ],
            [
                'profession_format' => '04_Medical_Technologist',
                'profession_name' => 'Medical Technologist', 
                'status' => '1'
            ],
            [
                'profession_format' => '05_Midwife',
                'profession_name' => 'Midwife', 
                'status' => '1'
            ],
            [
                'profession_format' => '06_Nurse',
                'profession_name' => 'Nurse', 
                'status' => '1'
            ],
            [
                'profession_format' => '07_Nutritionist_Dietician',
                'profession_name' => 'Nutritionist-Dietician', 
                'status' => '1'
            ],
            [
                'profession_format' => '08_Occupational_Therapist',
                'profession_name' => 'Occupational Therapist', 
                'status' => '1'
            ],
            [
                'profession_format' => '09_Optometrist',
                'profession_name' => 'Optometrist', 
                'status' => '1'
            ],
            [
                'profession_format' => '10_Pharmacist',
                'profession_name' => 'Pharmacist', 
                'status' => '1'
            ],
            [
                'profession_format' => '11_Physical_Therapist',
                'profession_name' => 'Physical Therapist', 
                'status' => '1'
            ],
            [
                'profession_format' => '12_Physician',
                'profession_name' => 'Physician', 
                'status' => '1'
            ],
            [
                'profession_format' => '13_Radiologic_Technologist',
                'profession_name' => 'Radiologic Technologist', 
                'status' => '1'
            ],
            [
                'profession_format' => '14_Respiratory_Therapist',
                'profession_name' => 'Respiratory Therapist', 
                'status' => '1'
            ],
            [
                'profession_format' => '15_X_ray_Technologist',
                'profession_name' => 'X ray Technologist', 
                'status' => '1'
            ],
             [
                'profession_format' => '16_Barangay_Health_Worker',
                'profession_name' => 'Barangay Health Worker', 
                'status' => '1'
            ],
             [
                'profession_format' => '17_Maintenance_Staff',
                'profession_name' => 'Maintenance Staff', 
                'status' => '1'
            ],
              [
                'profession_format' => '18_Administrative_Staff',
                'profession_name' => 'Administrative Staff', 
                'status' => '1'
            ],
            [
                'profession_format' => '19_Others_',
                'profession_name' => 'Other', 
                'status' => '1'
            ]
        ]);
    }
}
