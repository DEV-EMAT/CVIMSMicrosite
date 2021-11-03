<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarAttainmentSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('iskocab')->create('scholar_attainment_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('scholar_id')->nullable()->comment('scholar id');
            $table->foreign('scholar_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName('iskocab').'.scholars');
            $table->unsignedBigInteger('attainment_id')->nullable()->comment('educational attainment id');
            $table->foreign('attainment_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName('iskocab').'.educational_attainments');
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
        Schema::connection('iskocab')->dropIfExists('scholar_attainment_summaries');
    }
}
