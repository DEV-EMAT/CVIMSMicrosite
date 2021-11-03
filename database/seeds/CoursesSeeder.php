<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('iskocab')->table('courses')->insert([ 
           [   
               'course_code' => 'BSCS',
               'course_description' => 'BS COMPUTER SCIENCE',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
               'course_code' => 'BSA',
               'course_description' => 'BS ACCOUNTANCY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
               'course_code' => 'BSBA',
               'course_description' => 'BS BUSINESS ADMINISTRATION',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
               'course_code' => 'BS PSYCHOLOGY',
               'course_description' => 'BS PSYCHOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
               'course_code' => 'BSN',
               'course_description' => 'BS NURSING',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
               'course_code' => 'BS MEDTECH',
               'course_description' => 'BS MEDICAL TECHNOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
               'course_code' => 'BEED',
               'course_description' => 'BS ELEMENTARY EDUCATION',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
               'course_code' => 'BSCPE',
               'course_description' => 'BS COMPUTER ENGINEERING',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
               'course_code' => 'BSCE',
               'course_description' => 'BS CIVIL ENGINEERING',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
               'course_code' => 'BSEE',
               'course_description' => 'BS ELECTRICAL ENGINEERING',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
               'course_code' => 'BSED',
               'course_description' => 'BS SECONDARY EDUCATION',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
               'course_code' => 'MAT-THE / MASTERS',
               'course_description' => 'MASTERAL / MASTER OF ARTS IN TEACHING TECHNOLOGY AND HOME ECONONICS',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'AB HISTORY',
               'course_description' => 'AB HISTORY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'AB PHILOSOPHY',
               'course_description' => 'AB PHILOSOPHY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BFA',
               'course_description' => 'FINE ARTS MAJOR IN PAINTING',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BFA',
               'course_description' => 'FINE ARTS MAJOR IN SCULPTURE',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BFA',
               'course_description' => 'FINE ARTS MAJOR IN VISUAL COMMUNICATION',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'OTHERS',
               'course_description' => 'OTHERS',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'AB PSYCHOLOGY',
               'course_description' => 'AB PSYCHOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS CRIMINOLOGY',
               'course_description' => 'BS CRIMINOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'AB ECONOMICS',
               'course_description' => 'AB ECONOMICS',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS ECONOMICS',
               'course_description' => 'BS ECONOMICS',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'AB POLITICAL SCIENCE',
               'course_description' => 'AB POLITICAL SCIENCE',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'AB ENGLISH',
               'course_description' => 'AB ENGLISH',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'AB LINGUISTICS',
               'course_description' => 'AB LINGUISTICS',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'AB LITERATURE',
               'course_description' => 'AB LITERATURE',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'AB ANTHROPOLOGY',
               'course_description' => 'AB ANTHROPOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'AB SOCIOLOGY',
               'course_description' => 'AB SOCIOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'AB FILIPINPO',
               'course_description' => 'AB FILIPINO',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS FORENSIC SCIENCE',
               'course_description' => 'BS FORENSIC SCIENCE',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'AB ISLAMIC STUDIES',
               'course_description' => 'AB ISLAMIC STUDIES',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSES',
               'course_description' => 'BS ENVIRONMENT',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS FORESTRY',
               'course_description' => 'BS FORESTRY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSFI',
               'course_description' => 'BS FISHERIES',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS GEOLOGY',
               'course_description' => 'BS GEOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS BIOLOGY',
               'course_description' => 'BS BIOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS MOLECULAR BIOLOGY',
               'course_description' => 'BS MOLECULAR BIOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS PHYSICS',
               'course_description' => 'BS PHYSICS',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS CHEMISTRY',
               'course_description' => 'BS CHEMISTRY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
             'course_code' => 'BS MIDWIFERY',
             'course_description' => 'BS MIDWIFERY',
             'status' => '1'
 ,
 'created_at' => Carbon::now(),
'updated_at' => Carbon::now()           ],
         
           [   
             
              'course_code' => 'BSOT',
               'course_description' => 'BS OCCUPATIONAL THERAPY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
         
           [   
             
               'course_code' => 'BS PHARMACY',
               'course_description' => 'BS PHARMACY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSPT',
               'course_description' => 'BS PHYSICAL THERAPY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS RAD TECH',
               'course_description' => 'BS RADIOLOGIC TECHNOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSRT',
               'course_description' => 'BS RESPIRATORY THERAPY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSISLP',
               'course_description' => 'BS SPEECH LANGUAGE PATHOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSSS',
               'course_description' => 'BS SPORTS SCIENCE',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSIS',
               'course_description' => 'BS INFORMATION SYSTEMS',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS MATHEMATICS',
               'course_description' => 'BS MATHEMATICS',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS APPLIED MATH',
               'course_description' => 'BS APPLIED MATH',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS STAT',
               'course_description' => 'BS STATISTICS',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS AGRICULTURE',
               'course_description' => 'BS AGRICULTURE',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS AGRIBUSINESS',
               'course_description' => 'BS AGRIBUSINESS',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS AGROFORESTRY',
               'course_description' => 'BS AGROFORESTRY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS ARCHITECTURE',
               'course_description' => 'BS ARCHITECTURE',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BLA',
               'course_description' => 'BACHELOR IN LANDSCAPE ARCHITECTURE',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS INTERIOR DESIGN',
               'course_description' => 'BS INTERIOR DESIGN',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSACT',
               'course_description' => 'BS ACCOUNTING TECHNOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSBA-FM',
               'course_description' => 'BSBA MAJOR IN FINANCE MANAGEMENT',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSBA-HRDM',
               'course_description' => 'BSBA MAJOR IN HUMAN RESOURCE DEVELOPMENT MANAGEMENT',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSBA-MM',
               'course_description' => 'BSBA MARKETING MANAGEMENT',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSBA-OM',
               'course_description' => 'BSBA OPERATIONS MANAGEMENT',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSECE',
               'course_description' => 'BS ELECTRONICS ENGINEERING',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSTM',
               'course_description' => 'BS TOURISM MANAGEMENT',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS HRM',
               'course_description' => 'BS HOTEL AND RESTAURANT MANAGEMENT',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS ENTREP',
               'course_description' => 'BS ENTREPRENEUR',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSOA',
               'course_description' => 'BS OFFICE ADMINISTRATION',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSED-TECH',
               'course_description' => 'BSED MAJOR IN TECHNOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSED-BIOL',
               'course_description' => 'BSED MAJOR IN BIOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSED-ENG',
               'course_description' => 'BSED MAJOR IN ENGLISH',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSED-FIL',
               'course_description' => 'BSED MAJOR IN FILIPINO',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSED-MATH',
               'course_description' => 'BSED MAJOR IN MATH',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSED-ISLA',
               'course_description' => 'BSED MAJOR IN ISLAMIC STUDIES',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSED-MUSIC',
               'course_description' => 'BSED MAJOR IN MUSIC',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSED-PHYSICS',
               'course_description' => 'BSED MAJOR IN PHYSICS',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSED-SOCIAL STUDIES',
               'course_description' => 'BSED MAJOR IN SOCIAL STUDIES',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSED-VALUES',
               'course_description' => 'BSED MAJOR IN VALUES EDUCATION',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSIE',
               'course_description' => 'BS INDUSTRIAL ENGINEERING',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSMT',
               'course_description' => 'BS MARINE TRANSPORTATION',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSHM',
               'course_description' => 'BS HOSPITALITY MANAGEMENT',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'DOCTORATE',
               'course_description' => 'DOCTORATE',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BPED',
               'course_description' => 'BACHELOR OF PHYSICAL EDUCATION',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSMARE',
               'course_description' => 'BS MARINE ENGINEER',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSME',
               'course_description' => 'BS MECHANICAL ENGINEERING',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BSMEXE',
               'course_description' => 'BS MECHATRONICS ENGINEERING',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS DENTRISTRY',
               'course_description' => 'BS DENTRISTRY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS SOCIAL WORK',
               'course_description' => 'BS SOCIAL WORK',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BTV TEACHER EDUCATION',
               'course_description' => 'BACHELOR OF TECHNICAL VOCATIONAL TEACHER EDUCATION',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BS CHEM ENG',
               'course_description' => 'BS CHEMICAL ENGINEERING',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
           
           [   
             
               'course_code' => 'BA PSYCHOLOGY',
               'course_description' => 'BA PSYCHOLOGY',
               'status' => '1',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
         
           [   
             
               'course_code' => 'BSIT',
               'course_description' => 'BS INFORMATION TECHNOLOGY',
               'status' =>'1' ,
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now()
           ],
        ]);
    }
}
