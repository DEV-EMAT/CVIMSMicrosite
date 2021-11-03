<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('comprehensive')->create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('question');
            $table->string('answer');
            $table->text('choices');
            $table->unsignedBigInteger('exam_subject_id');
            $table->foreign('exam_subject_id')
                ->nullable()
                ->constrained()
                ->references('id')->on('exam_subjects');
            $table->unsignedBigInteger('exam_type_id');
            $table->foreign('exam_type_id')
                ->nullable()
                ->constrained()
                ->references('id')->on('exam_types');
            $table->string('status');
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
        Schema::connection('comprehensive')->dropIfExists('questions');
    }
}