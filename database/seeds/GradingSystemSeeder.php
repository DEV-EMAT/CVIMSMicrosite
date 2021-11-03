<?php

use Illuminate\Database\Seeder;

class GradingSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::connection('iskocab')->table('grading_systems')->insert([
            ['id' => '1','school_name' => 'WESTBRIDGE INSTITUTE OF TECHNOLOGY','address' => 'CABUYAO, LAGUNA','status' => '1','created_at' => '2020-02-20 07:07:43','updated_at' => '2020-02-20 07:07:43'],
  
            ['id' => '2','school_name' => 'ST. VINCENT COLLEGE OF CABUYAO (SVCC)','address' => 'MAMATID RD, CABUYAO, XMR07:10:35'],
            
            ['id' => '3','school_name' => 'SYSTEMS TECHNOLOGY INSTITUTE (STI)','address' => '---','status' => '1','created_at' => '2020-02-20 07:13:50','updated_at' => '2020-02-20 07:19:37'],
            
            ['id' => '4','school_name' => 'UNIVERSITY OF PERPETUAL HELP SYSTEM','address' => '---','status' => '1','created_at' => '2020-02-20 07:18:02','updated_at' => '2020-02-23 04:55:52'],
            
            ['id' => '5','school_name' => 'PAMANTASAN NG CABUYAO (PNC)','address' => 'KATAPAN, BANAY-BANAY, CABUYAO, LAGUNA','status' => '1','created_at' => '2020-02-20 07:18:03','updated_at' => '2020-02-20 07:19:29'],
            
            ['id' => '6','school_name' => 'POLYTECHNIC UNIVERSITY OF THE PHILIPPINES (PUP)','address' => '---','status' => '1','created_at' => '2020-02-21 01:07:00','updated_at' => '2020-02-21 01:08:36'],
            
            ['id' => '7','school_name' => 'ST. MICHAEL\'S COLLEGE','address' => '---','status' => '1','created_at' => '2020-02-21 01:12:16','updated_at' => '2020-02-21 01:12:16'],
            
            ['id' => '8','school_name' => 'LYCEUM OF PHILIPPINES UNIVERSITY (LPU)','address' => '---','status' => '1','created_at' => '2020-02-21 01:21:04','updated_at' => '2020-02-21 01:21:30'],
            
            ['id' => '9','school_name' => 'CAVITE STATE UNIVERSITY','address' => 'CAVITE','status' => '1','created_at' => '2020-02-21 01:21:52','updated_at' => '2020-02-24 15:47:53'],
            
            ['id' => '10','school_name' => 'MALAYAN COLLEGES LAGUNA (MCL)','address' => 'PULO, CABUYAO, LAGUNA','status' => '1','created_at' => '2020-02-21 01:28:21','updated_at' => '2020-02-24 15:47:20'],
            
            ['id' => '11','school_name' => 'LAGUNA STATE POLYTECHNIC UNIVERSITY (LSPU)','address' => 'SAN PABLO, LAGUNA','status' => '1','created_at' => '2020-02-21 01:32:03','updated_at' => '2020-02-24 15:49:45'],
            
            ['id' => '12','school_name' => 'DE LA SALLE UNIVERSITY (DLSU)','address' => '---','status' => '1','created_at' => '2020-02-21 01:34:14','updated_at' => '2020-02-22 08:59:24'],
            
            ['id' => '13','school_name' => 'TECHNOLOGICAL UNIVERSITY OF THE PHILIPPINES (TUP)','address' => '---','status' => '1','created_at' => '2020-02-21 01:34:22','updated_at' => '2020-02-21 01:34:22'],
            
            ['id' => '14','school_name' => 'OUR LADY OF ASSUMPTION COLLEGE','address' => '---','status' => '1','created_at' => '2020-02-21 01:39:06','updated_at' => '2020-02-21 01:39:06'],
            
            ['id' => '15','school_name' => 'COLEGIO DE SAN JUAN DE LETRAN','address' => '---','status' => '1','created_at' => '2020-02-21 01:42:12','updated_at' => '2020-02-21 01:42:12'],
            
            ['id' => '16','school_name' => 'AMA COMPUTER COLLEGE, INC. (AMA)','address' => '---','status' => '1','created_at' => '2020-02-21 01:43:57','updated_at' => '2020-02-21 01:43:57'],
            
            ['id' => '17','school_name' => 'CENTRO ESCOLAR UNIVERSITY','address' => '---','status' => '1','created_at' => '2020-02-21 01:43:59','updated_at' => '2020-02-21 01:43:59'],
            
            ['id' => '18','school_name' => 'PAMANTASAN NG LUNGSOD NG MAYNILA','address' => '---','status' => '1','created_at' => '2020-02-21 01:47:59','updated_at' => '2020-02-21 01:47:59'],
            
            ['id' => '19','school_name' => 'CITI GLOBAL COLLEGE (CGC)','address' => 'CABUYAO, LAGUNA','status' => '1','created_at' => '2020-02-21 01:51:52','updated_at' => '2020-03-06 03:52:35'],
            
            ['id' => '20','school_name' => 'LAGUNA UNIVERSITY','address' => 'SANTA CRUZ, LAGUNA','status' => '1','created_at' => '2020-02-21 01:57:19','updated_at' => '2020-02-24 15:50:30'],
            
            ['id' => '21','school_name' => 'UNIVERSITY OF THE CITY OF MUNTINLUPA','address' => 'MUNTINLUPA','status' => '1','created_at' => '2020-02-21 02:03:56','updated_at' => '2020-02-24 15:51:45'],
            
            ['id' => '22','school_name' => 'ADVENTIST UNIVERSITY OF THE PHILIPPINES (AUP)','address' => '---','status' => '1','created_at' => '2020-02-21 02:04:54','updated_at' => '2020-02-21 02:04:54'],
            
            ['id' => '23','school_name' => 'LAGUNA COLLEGE OF BUSINESS AND ARTS (LCBA)','address' => 'CALAMBA, LAGUNA','status' => '1','created_at' => '2020-02-21 02:21:53','updated_at' => '2020-02-21 02:21:53'],
            
            ['id' => '24','school_name' => 'EULOGIO "AMANG" RODRIGUEZ INSTITUTE OF SCIENCE AND TECHNOLOGY','address' => '---','status' => '1','created_at' => '2020-02-21 02:21:57','updated_at' => '2020-02-21 02:21:57'],
            
            ['id' => '25','school_name' => 'PHILIPPINE WOMEN\'S UNIVERSITY (PWU)','address' => '---','status' => '1','created_at' => '2020-02-21 02:21:59','updated_at' => '2020-02-21 02:21:59'],
            
            ['id' => '26','school_name' => 'CALAMBA CITY COLLEGE','address' => 'CALAMBA, LAGUNA','status' => '1','created_at' => '2020-02-21 02:22:03','updated_at' => '2020-02-21 02:22:03'],
            
            ['id' => '27','school_name' => 'ST. MARY MAGDALENE COLLEGE','address' => '---','status' => '1','created_at' => '2020-02-21 02:25:35','updated_at' => '2020-02-21 02:25:35'],
            
            ['id' => '28','school_name' => 'SAN PEDRO COLLEGE OF BUSINESS ADMINISTRATION (SPCBA)','address' => 'SAN PEDRO, LAGUNA','status' => '1','created_at' => '2020-02-21 02:29:13','updated_at' => '2020-02-21 02:29:13'],
            
            ['id' => '29','school_name' => 'NATIONAL UNIVERSITY','address' => '---','status' => '1','created_at' => '2020-02-21 02:29:27','updated_at' => '2020-02-21 02:29:27'],
            
            ['id' => '30','school_name' => 'RIZAL COLLEGE','address' => '---','status' => '1','created_at' => '2020-02-21 02:31:08','updated_at' => '2020-02-21 02:31:08'],
            
            ['id' => '31','school_name' => 'NEW SINAI SCHOOL AND COLLEGES','address' => 'SANTA ROSA, LAGUNA','status' => '1','created_at' => '2020-02-21 02:33:39','updated_at' => '2020-02-21 02:33:39'],
            
            ['id' => '32','school_name' => 'PHILIPPINE STATE COLLEGE OF AERONAUTICS','address' => '---','status' => '1','created_at' => '2020-02-21 02:34:26','updated_at' => '2020-02-21 02:34:26'],
            
            ['id' => '33','school_name' => 'FAR EASTERN UNIVERSITY (FEU)','address' => '---','status' => '1','created_at' => '2020-02-21 02:35:20','updated_at' => '2020-02-21 02:35:20'],
            
            ['id' => '34','school_name' => 'CALAMBA DOCTORS\' COLLEGE','address' => 'CALAMBA, LAGUNA','status' => '1','created_at' => '2020-02-21 02:46:37','updated_at' => '2020-02-21 02:49:19'],
            
            ['id' => '35','school_name' => 'BATANGAS STATE UNIVERSITY','address' => 'BATANGAS','status' => '1','created_at' => '2020-02-21 02:47:31','updated_at' => '2020-02-21 02:47:31'],
            
            ['id' => '36','school_name' => 'UNKNOWN','address' => '---','status' => '1','created_at' => '2020-02-21 02:47:42','updated_at' => '2020-02-21 02:47:42'],
            
            ['id' => '37','school_name' => 'ADAMSON UNIVERSITY','address' => '---','status' => '1','created_at' => '2020-02-21 02:48:02','updated_at' => '2020-02-21 02:48:02'],
            
            ['id' => '38','school_name' => 'ASIA TECHNOLOGICAL SCHOOL OF SCIENCE AND ARTS','address' => '---','status' => '1','created_at' => '2020-02-21 06:47:05','updated_at' => '2020-02-21 06:47:05'],
            
            ['id' => '39','school_name' => 'COLEGIO DE DAGUPAN','address' => 'DAGUPAN','status' => '1','created_at' => '2020-02-22 08:22:44','updated_at' => '2020-02-22 08:22:44'],
            
            ['id' => '40','school_name' => 'SOUTHERN LUZON STATE','address' => '---','status' => '1','created_at' => '2020-02-22 08:27:23','updated_at' => '2020-02-22 08:28:51'],
            
            ['id' => 'XMR-02-22 08:32:05','updated_at' => '2020-02-22 08:32:05'],
            
            ['id' => '42','school_name' => 'COLLEGE OF SAINT BENILDE','address' => '---','status' => '1','created_at' => '2020-02-22 10:07:51','updated_at' => '2020-02-22 10:07:51'],
            
            ['id' => '43','school_name' => 'ASIAN INSTITUTE OF TECHNOLOGY, SCIENCES AND THE ARTS, INC. (AITSA)','address' => 'POBLACION UNO','status' => '1','created_at' => '2020-02-22 10:16:13','updated_at' => '2020-02-22 10:16:13'],

            ['id' => '44','school_name' => 'PHILIPPINE NORMALS UNIVERSITY (PNU)','address' => 'POBLACION UNO','status' => '1','created_at' => '2020-02-22 10:16:13','updated_at' => '2020-02-22 10:16:13'],
            
            ['id' => '45','school_name' => 'UNIVERSITY OF SANTO TOMAS (UST)','address' => '---','status' => '1','created_at' => '2020-02-22 19:40:12','updated_at' => '2020-02-22 19:40:12'],
            
            ['id' => '46','school_name' => 'LAGUNA NORTHWESTERN COLLEGE','address' => 'LAGUNA','status' => '1','created_at' => '2020-02-23 04:54:14','updated_at' => '2020-02-23 04:54:14'],
            
            ['id' => '47','school_name' => 'PACIFIC INTERCONTINENTAL COLLEGE','address' => '---','status' => '1','created_at' => '2020-02-23 05:35:00','updated_at' => '2020-02-23 05:35:00'],
            
            ['id' => '48','school_name' => 'PHILIPPINE STATE COLLEGE OF AERONAUTICS','address' => 'PASAY','status' => '1','created_at' => NULL,'updated_at' => NULL],
            
            ['id' => '49','school_name' => 'UNIVERSITY OF THE PHILIPPINES (UP)','address' => '---','status' => '1','created_at' => '2020-02-23 06:02:57','updated_at' => '2020-02-23 06:02:57'],
            
            ['id' => '50','school_name' => 'SAN PABLO COLLEGES','address' => 'SAN PABLO, LAGUNA','status' => '1','created_at' => '2020-02-23 07:54:49','updated_at' => '2020-02-24 15:51:08'],
            
            ['id' => '51','school_name' => 'SAN SEBASTIAN COLLEGE','address' => '---','status' => '1','created_at' => '2020-02-23 08:39:17','updated_at' => '2020-02-23 08:39:17'],
            
            ['id' => '52','school_name' => 'MANILA ADVENTIST COLLEGE','address' => 'MANILA','status' => '1','created_at' => '2020-02-24 12:03:03','updated_at' => '2020-02-24 12:03:03'],
            
            ['id' => '53','school_name' => 'TECHNOLOGICAL INSTITUTE OF THE PHILIPPINES','address' => '---','status' => '1','created_at' => '2020-02-24 12:18:14','updated_at' => '2020-02-24 12:18:14'],
            
            ['id' => '54','school_name' => 'TRIMEX COLLEGES, INC.','address' => 'BIÑAN, LAGUNA','status' => '1','created_at' => '2020-02-24 12:22:38','updated_at' => '2020-02-24 12:22:38'],
            
            ['id' => '55','school_name' => 'RIZAL TECHNOLOGICAL UNIVERSITY','address' => '---','status' => '1','created_at' => '2020-02-24 12:33:28','updated_at' => '2020-02-24 12:33:28'],
            
            ['id' => '56','school_name' => 'PHILIPPINE MERCHANT MARINE SCHOOL','address' => '---','status' => '1','created_at' => '2020-02-24 12:36:34','updated_at' => '2020-02-24 12:36:34'],
            
            ['id' => '57','school_name' => 'ST. SCHOLASTICA\'S COLLEGE','address' => '---','status' => '1','created_at' => '2020-02-24 14:41:01','updated_at' => '2020-02-24 14:41:01'],
            
            ['id' => '58','school_name' => 'PHILIPPINE COLLEGE OF HEALTH SCIENCES (PCHS), INC.','address' => 'MANILA','status' => '1','created_at' => '2020-02-24 15:21:29','updated_at' => '2020-02-24 15:21:29'],
            
            ['id' => '59','school_name' => 'OUR LADY OF FATIMA UNIVERSITY','address' => '---','status' => '1','created_at' => '2020-02-24 15:27:52','updated_at' => '2020-02-24 15:27:52'],
            
            ['id' => '60','school_name' => 'BALIAN COMMUNITY COLLEGE (BCC)','address' => '---','status' => '1','created_at' => '2020-02-24 15:47:10','updated_at' => '2020-02-24 15:47:10'],
            
            ['id' => '61','school_name' => 'REPUBLIC COLLEGES OF GUINOBATAN, INC.','address' => 'GUINOBATAN, ALBAY','status' => '1','created_at' => '2020-02-24 16:26:27','updated_at' => '2020-02-24 16:26:27'],
            
            ['id' => '62','school_name' => 'ST. IGNATIUS','address' => '---','status' => '1','created_at' => '2020-02-24 16:36:50','updated_at' => '2020-02-24 16:36:50'],
            
            ['id' => '63','school_name' => 'DON BOSCO','address' => '---','status' => '1','created_at' => '2020-02-24 18:37:24','updated_at' => '2020-02-24 18:37:24'],
            
            ['id' => '64','school_name' => 'TRACE COLLEGE','address' => '---','status' => '1','created_at' => '2020-03-02 20:28:16','updated_at' => '2020-03-02 20:28:16'],
            
            ['id' => '65','school_name' => 'EMILIO AGUINALDO COLLEGE','address' => '---','status' => '1','created_at' => '2020-03-02 20:44:47','updated_at' => '2020-03-02 20:44:47'],
            
            ['id' => '66','school_name' => 'SAN BEDA COLLEGE','address' => '---','status' => '1','created_at' => '2020-03-02 22:49:41','updated_at' => '2020-03-02 22:49:41'],
            
            ['id' => '67','school_name' => 'NATIONAL TEACHERS COLLEGE','address' => 'QUIAPO, MANILA','status' => '1','created_at' => '2020-03-04 01:48:35','updated_at' => '2020-03-04 01:48:35'],
            
            ['id' => '68','school_name' => 'NEW ERA UNIVERSITY','address' => '---','status' => '1','created_at' => '2020-03-05 21:00:33','updated_at' => '2020-03-05 21:00:33'],
            
            ['id' => '69','school_name' => 'SAINT BENILDE','address' => '---','status' => '1','created_at' => '2020-03-07 16:35:11','updated_at' => '2020-03-07 16:35:11'],
        ]);
    }
}
