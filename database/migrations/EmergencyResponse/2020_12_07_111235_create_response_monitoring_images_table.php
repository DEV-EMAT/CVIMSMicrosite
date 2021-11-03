<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponseMonitoringImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('emergencyresponse')->create('response_monitoring_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('incident_report_id');
            $table->foreign('incident_report_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('incident_reports');
            $table->longText('image_path')->nullable()->comment('path, url of images [Serialized multiple image maximum of 3 image per incident]');
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
        Schema::connection('emergencyresponse')->dropIfExists('response_monitoring_images');
    }
}
