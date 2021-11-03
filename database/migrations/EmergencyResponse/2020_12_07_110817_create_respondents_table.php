<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespondentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::connection('emergencyresponse')->create('respondents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('incident_monitoring_id');
            $table->foreign('incident_monitoring_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('incident_monitorings');
            $table->integer('user_id')->nullable()->comment('staff or rescuer'); 
            $table->string('fullname')->nullable();
            $table->string('gender')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('address')->nullable();
            $table->string('address_id')->nullable();
            $table->string('contact_number')->nullable();
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
         Schema::connection('emergencyresponse')->dropIfExists('respondents');
    }
}
