<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientMonitoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid_tracer')->create('patient_monitorings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('investigator_has_patients_id')->nullable();
            $table->foreign('investigator_has_patients_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('investigator_has_patients');
            $table->string('body_temperature')->nullable();
            $table->string('patient_status')->nullable();
            $table->string('patient_monitoring_status')->nullable();
            $table->string('confinement')->nullable();
            $table->string('facility')->nullable();
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
        Schema::connection('covid_tracer')->dropIfExists('patient_monitorings');
    }
}
