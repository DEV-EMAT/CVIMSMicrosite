<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationalAttainmentHasExaminationModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('iskocab')->create('educational_attainment_has_examination_modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('exam_id')->nullable()->comment('examination module id');
            $table->foreign('exam_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on(connectionName('comprehensive').'.exam_titles');
             //[start]from ecabs program services 
            $table->unsignedBigInteger('prog_id')->nullable()->comment('program sevices id from ecabs db');
            $table->foreign('prog_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on(connectionName('comprehensive').'.program_services');
             //[end] from ecabs program services

            $table->unsignedBigInteger('ea_id')->nullable()->comment('educational attainment id');
            $table->foreign('ea_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('educational_attainments');
            $table->string('status')->nullabe();
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
        Schema::connection('iskocab')->dropIfExists('educational_attainment_has_examination_modules');
    }
}
