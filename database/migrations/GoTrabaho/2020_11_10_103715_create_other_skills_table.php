<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_skills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('personal_info_id')->nullable();
            $table->foreign('personal_info_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on('personal_information');
            $table->string('skill_description')->nullable();
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
        Schema::dropIfExists('other_skills');
    }
}
