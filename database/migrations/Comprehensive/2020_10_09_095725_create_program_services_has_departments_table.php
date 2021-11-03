<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramServicesHasDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('comprehensive')->create('program_services_has_departments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('program_services_id');
            $table->foreign('program_services_id')
                    ->nullable()
                  ->constrained()
                  ->references('id')->on('program_services');
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')
                    ->nullable()
                  ->constrained()
                  ->references('id')->on(connectionName().'.departments');
            $table->string('status')->nullable();
            
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
        Schema::connection('comprehensive')->dropIfExists('program_services_has_departments');
    }
}
