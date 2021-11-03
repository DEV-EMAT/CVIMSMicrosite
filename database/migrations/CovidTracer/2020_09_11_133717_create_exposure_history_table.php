<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExposureHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::connection('covid_tracer')->create('exposure_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('patient_profile_id')->nullable();
            $table->foreign('patient_profile_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName('covid_tracer').'.patient_profiles');
            $table->unsignedBigInteger('place_of_assignment_id')->nullable();
            $table->foreign('place_of_assignment_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName('covid_tracer').'.place_of_assignments');
            $table->string('date_of_exposure')->nullable();
            $table->string('time_of_exposure')->nullable();
            $table->longText('mode_of_transportation')->nullable();
            $table->longText('places_of_engagement')->nullable();
            $table->longText('person_enteracted_with')->nullable();
            $table->string('tracked_status')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('exposure_status')->nullable();
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
       Schema::connection('covid_tracer')->dropIfExists('exposure_histories');
    }
}
