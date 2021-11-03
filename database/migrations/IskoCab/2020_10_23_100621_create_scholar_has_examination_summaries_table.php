<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarHasExaminationSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('iskocab')->create('scholar_has_examination_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('application_id')->nullable();
            $table->foreign('application_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on('scholar_has_applications');
            $table->unsignedBigInteger('exam_title_id')->nullable();
            $table->foreign('exam_title_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on(connectionName('comprehensive').'.exam_titles');
            $table->string('score')->nullable()->comment('score of test e.g.[20/30] [null default]');
            $table->longText('answer_sheet')->nullable()->comment('list of exam already taken [null default]');
            $table->string('examination_result')->nullable()->comment('PASSED | FAILED');
            $table->string('examination_status')->nullable()->comment('[0 = exam not yet done, 1 = exam already done]');
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
        Schema::connection('iskocab')->dropIfExists('scholar_has_examination_summaries');
    }
}
