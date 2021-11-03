<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarHasRequirementSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('iskocab')->create('scholar_has_requirement_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('application_id')->nullable()->comment('scholar_has_application_id');
            $table->foreign('application_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('scholar_has_applications');
            $table->longtext('requirement_list')->nullable()->comment('format array[reqList=> "{?}"]');
            $table->string('status')->nullable()->comment('[INCOMPLETE, COMPLETE, PENDING]');
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
        Schema::connection('iskocab')->dropIfExists('scholar_has_requirement_summaries');
    }
}
