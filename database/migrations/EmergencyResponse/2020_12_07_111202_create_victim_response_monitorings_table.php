<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVictimResponseMonitoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::connection('emergencyresponse')->create('victim_response_monitorings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('incident_report_id');
            $table->foreign('incident_report_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('incident_reports');
            $table->unsignedBigInteger('victim_id');
            $table->foreign('victim_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('victim_profiles');
            $table->longText('victim_injuries_remarks');
            $table->longText('victim_status')->comment('INJURED, NORMAL etc..');
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
         Schema::connection('emergencyresponse')->dropIfExists('victim_response_monitorings');
    }
}
