<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestigatorHasPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid_tracer')->create('investigator_has_patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('patient_roots_id')->nullable();
            $table->foreign('patient_roots_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('patient_roots');
            $table->unsignedBigInteger('place_of_assignments_id')->nullable();
            $table->foreign('place_of_assignments_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('place_of_assignments');
            $table->unsignedBigInteger('patient_profile_id')->nullable();
            $table->foreign('patient_profile_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('patient_profiles');
            $table->string('classification')->nullable();
            $table->string('status')->nullable();
            $table->string('interview_time_stamp')->nullable();
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
        Schema::connection('covid_tracer')->dropIfExists('investigator_has_patients');
    }
}
