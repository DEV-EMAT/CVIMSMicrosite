<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipProgramModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('iskocab')->create('scholarship_program_modules', function (Blueprint $table) {
            $table->bigIncrements('id');
             //[start]from ecabs program services 
            $table->unsignedBigInteger('program_id')->nullable()->comment('program sevices id from ecabs db');
            $table->foreign('program_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on(connectionName('comprehensive').'.program_services');
             //[end] from ecabs program services
            $table->unsignedBigInteger('ea_id')->nullable()->comment('educational attainment id');
            $table->foreign('ea_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on('educational_attainments');

            $table->string('number_of_units')->nullabe();
            $table->string('required_grade')->nullabe()->comment('[0 not required | 1 required]');
            $table->string('required_exam')->nullabe()->comment('[0 not required | 1 required]');
            $table->string('required_requirements')->nullabe()->comment('[0 not required | 1 required]');  
            $table->string('required_event')->nullabe()->comment('[0 not required | 1 required]');       
            $table->string('required_year')->nullabe()->comment('[0 not required | 1 required]');  
            $table->string('accept_passing_grade')->nullabe()->comment('[0 not allowed | 1 allowed]');
            $table->string('application_status')->nullabe()->comment('[0 not active | 1 active]');
            $table->string('status')->nullabe();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('iskocab')->dropIfExists('scholarship_program_modules'); 
    }
}
