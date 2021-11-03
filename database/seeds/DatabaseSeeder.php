<?php

use App\Ecabs\Maintenance;
use App\Ecabs\PersonDepartmentPosition;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Ecabs\Update;
use App\Ecabs\UpdateAccountDepartment;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(BarangaysTableSeeder::class);
        // $this->call(AddressTableSeeder::class);
        // $this->call(PeopleTableSeeder::class);
        // $this->call(UserTableSeeder::class);
        // $this->call(PositionAccessTableSeeder::class);
        // $this->call(DepartmentTableSeeder::class);
        // $this->call(EstablishmentCategoriesSeeder::class);
        // $this->call(PlatformSeeder::class);
        // $this->call(ExamTypeSeeder::class);
        // $this->call(MaintenanceSeeder::class);
        // $this->call(CoursesSeeder::class);
        // $this->call(EducationalAttainmentSeeder::class);
        // $this->call(EstablishmentInformationSeeder::class);

        $this->call(VaxBarangaySeeder::class);
        $this->call(VaxCategorySeeder::class);
        $this->call(VaxEmploymentTypeSeeder::class);
        $this->call(VaxProfessionsTypeSeeder::class);
        $this->call(VaxIdCategorySeeder::class);

        // $faker = Faker\Factory::create();

        // $array = array('Blog', 'Entertainment', 'News');
        // foreach (range(1, 5000) as $index)
        // {
        //     $new_update = new Update();
        //     $new_update->category = $faker->randomElement($array);
        //     $new_update->title = $faker->text(50);
        //     $new_update->content_path = '20201.xml';
        //     $new_update->images_path = 'a:2:{i:0;s:10:"202011.png";i:1;s:10:"202012.png";}';
        //     $new_update->status = 1;

        //     if($new_update->save()){
        //         $update_account_dept = new UpdateAccountDepartment();
        //         $update_account_dept->update_id = $new_update->id;
        //         $update_account_dept->user_id = 1;
        //         $update_account_dept->merging_dept_id = 1;
        //         $update_account_dept->status = 1;
        //         $update_account_dept->save();
        //     }
        // }

    }
}
