<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignsSymptomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid_tracer')->create('signs_symptoms', function (Blueprint $table) {
            $table->bigIncrements('id');
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
            $table->string('fever_degree')->nullable();
            $table->string('cough')->nullable();
            $table->string('sore_throat')->nullable();
            $table->string('colds')->nullable();
            $table->string('shortness_difficulty_of_breathing')->nullable();
            $table->string('vomiting')->nullable();
            $table->string('diarrhea')->nullable();
            $table->string('fatigue_chills')->nullable();
            $table->string('headache')->nullable();
            $table->string('joint_pains')->nullable();
            $table->string('other_symptoms')->nullable();
            $table->string('daily_conditions')->nullable();
            $table->string('date_of_consultation')->nullable();
            $table->string('date_of_discharge')->nullable();
            $table->string('name_of_informant')->nullable();
            $table->string('relationship')->nullable();
            $table->string('relationship_phone_no')->nullable();
            $table->string('signs_symptoms_status')->nullable()->comment('1=ongoing,2=finish of monitoring,3=dead');
            $table->string('identifier')->nullable();
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
        Schema::connection('covid_tracer')->dropIfExists('signs_symptoms');
    }
}
