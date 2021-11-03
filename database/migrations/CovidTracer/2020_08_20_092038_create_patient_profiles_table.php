<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid_tracer')->create('patient_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('affiliation')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('social_sector')->nullable();
            $table->string('workplace')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('nationality')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('email')->nullable();
            $table->string('employer_name')->nullable();
            $table->string('occupation')->nullable();
            $table->string('place_of_work_overseas')->nullable();
            $table->string('port_of_exit')->nullable();
            $table->string('airline_sea_vessel')->nullable();
            $table->string('flight_vessel_number')->nullable();
            $table->string('date_of_departure')->nullable();
            $table->string('date_of_arrival_in_philippines')->nullable();
            $table->string('date_of_contact_if_yes')->nullable();
            $table->string('his_of_exposure')->nullable();
            $table->string('clinical_status')->nullable();
            $table->string('date_of_onset_of_illness')->nullable();
            $table->string('date_of_admission_consultation')->nullable();
            $table->string('history_of_other_illness')->nullable();
            $table->string('chest_xray')->nullable();
            $table->string('pregnant')->nullable();
            $table->string('cxr_result')->nullable();
            $table->string('other_radiologic_findings')->nullable();
            $table->string('date_interview')->nullable();
            $table->string('classification')->nullable();
            $table->string('final_classification')->nullable();
            $table->string('place_of_interview')->nullable();
            $table->string('risk_exposure')->nullable();
            $table->string('isolation_facility')->nullable();
            $table->string('outcome')->nullable();
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
        Schema::connection('covid_tracer')->dropIfExists('patient_profiles');
    }
}
