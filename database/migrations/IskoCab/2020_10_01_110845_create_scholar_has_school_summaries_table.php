<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarHasSchoolSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('iskocab')->create('scholar_has_school_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('scholar_id')->nullable();
            $table->foreign('scholar_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('scholars');

            $table->unsignedBigInteger('school_id')->nullable();
            $table->foreign('school_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('schools');

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
        Schema::connection('iskocab')->dropIfExists('scholar_has_school_summaries');
    }
}
