<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidentMonitoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::connection('emergencyresponse')->create('incident_monitorings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('incident_report_id');
            $table->foreign('incident_report_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('incident_reports');
                    
            $table->string('incident_status')->nullable()->comment('YELLOW, ORANGE, RED WARNING'); 
            $table->longText('monitoring_remarks')->nullable()->comment('remarks');

            $table->string('monitoredby_id')->nullable()->comment('put user id');
            $table->string('monitored_by')->nullable()->comment('Fullname');
            
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
         Schema::connection('emergencyresponse')->dropIfExists('incident_monitorings');
    }
}
