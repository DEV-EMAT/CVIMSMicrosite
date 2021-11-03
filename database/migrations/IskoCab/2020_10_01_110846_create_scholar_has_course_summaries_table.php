<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarHasCourseSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholar_has_course_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('scholar_id')->nullable();
            $table->foreign('scholar_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('scholars');

            $table->unsignedBigInteger('course_id')->nullable();
            $table->foreign('course_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('courses');

            $table->string('status')->nullable()->comment('0 = false, 1 = active | get always the active data');
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
        Schema::dropIfExists('scholar_has_course_summaries');
    }
}
