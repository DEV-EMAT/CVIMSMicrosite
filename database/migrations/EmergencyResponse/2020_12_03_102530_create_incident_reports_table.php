<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('emergencyresponse')->create('incident_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('location_map')->nullable()->comment('incident discription');
            $table->longText('incident_address')->nullable()->comment('incident discription');
            $table->longText('incident_discription')->nullable()->comment('incident discription');
            $table->string('incident_category')->nullable()->comment('FIRE, FLOOD, ACCIDENT CRIME, MEDICAL ASSISTANCE');
            $table->string('weather_condition')->nullable()->comment('RAINY, SUNNY, CLOUDY etc..');
            $table->longText('witness')->nullable()->comment('witness statements');
            $table->longText('witness_statements')->nullable()->comment('remarks');
            $table->string('date_of_response')->nullable()->comment('remarks');
            $table->string('monitoring_status')->nullable()->comment('remarks');
            $table->string('category')->nullable()->comment('MANUALLY or GENERATED [mannually = non using mobile app | GENERATED = used app for SOS]');
            $table->string('report_by')->nullable()->comment('fullname');
            $table->string('encode_by')->nullable()->comment('fullname');
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
        Schema::connection('emergencyresponse')->dropIfExists('incident_reports');
    }
}
