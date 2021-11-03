<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinationMonitoringSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::connection('covid19vaccine')->create('vaccination_monitoring_surveys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vaccination_monitoring_id')->nullable();
            $table->foreign('vaccination_monitoring_id')
                  ->constrained()
                  ->nullable()
                  ->references('id')->on('vaccination_monitorings');
            $table->longText('question_1')->nullable();
            $table->longText('question_2')->nullable();
            $table->longText('question_3')->nullable();
            $table->longText('question_4')->nullable();
            $table->longText('question_5')->nullable();
            $table->longText('question_6')->nullable();
            $table->longText('question_7')->nullable();
            $table->longText('question_8')->nullable();
            $table->longText('question_9')->nullable();
            $table->longText('question_10')->nullable();
            $table->longText('question_11')->nullable();
            $table->longText('question_12')->nullable();
            $table->longText('question_13')->nullable();
            $table->longText('question_14')->nullable();
            $table->longText('question_15')->nullable();
            $table->longText('question_16')->nullable();
            $table->longText('question_17')->nullable();
            $table->longText('question_18')->nullable();
            $table->longText('question_19')->nullable();
            $table->longText('status')->nullable();
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
       Schema::connection('covid19vaccine')->dropIfExists('vaccination_monitoring_surveys');
    }
}
