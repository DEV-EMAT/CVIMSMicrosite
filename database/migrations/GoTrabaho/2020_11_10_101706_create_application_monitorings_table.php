<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationMonitoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_monitorings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('personal_info_id')->nullable();
            $table->foreign('personal_info_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on('personal_information');
            $table->unsignedBigInteger('job_application_id')->nullable();
            $table->foreign('job_application_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on('job_applications');
            $table->string('application_remarks')->nullable();
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
        Schema::dropIfExists('application_monitorings');
    }
}
