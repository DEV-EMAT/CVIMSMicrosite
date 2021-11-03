<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('personal_info_id')->nullable();
            $table->foreign('personal_info_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on('personal_information');
            $table->string('company_name')->nullable();
            $table->longText('address')->nullable();
            $table->string('inclusive_date_from')->nullable();
            $table->string('inclusive_date_to')->nullable();
            $table->string('position_held')->nullable();
            $table->string('formal_education')->nullable();
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
        Schema::dropIfExists('work_experiences');
    }
}
