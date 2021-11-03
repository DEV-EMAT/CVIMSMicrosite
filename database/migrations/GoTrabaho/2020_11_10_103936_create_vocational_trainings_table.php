<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVocationalTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vocational_trainings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('personal_info_id')->nullable();
            $table->foreign('personal_info_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on('personal_information');
            $table->string('name_of_training')->nullable();
            $table->string('skill_acquired')->nullable();
            $table->string('yr_of_exp')->nullable();
            $table->string('cert_received')->nullable();
            $table->string('issuing_school_agency')->nullable();
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
        Schema::dropIfExists('vocational_trainings');
    }
}
