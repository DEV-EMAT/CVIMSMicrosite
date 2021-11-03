<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid19vaccine')->create('surveys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('registration_id')->nullable();
            $table->foreign('registration_id')
                  ->constrained()
                  ->nullable()
                  ->references('id')->on('pre_registrations');
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
        Schema::connection('covid19vaccine')->dropIfExists('surveys');
    }
}
