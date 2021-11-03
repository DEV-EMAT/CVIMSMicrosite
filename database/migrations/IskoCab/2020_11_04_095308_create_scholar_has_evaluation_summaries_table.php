<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarHasEvaluationSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('iskocab')->create('scholar_has_evaluation_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('application_id')->nullable()->comment('scholar_has_application_id');
            $table->foreign('application_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('scholar_has_applications');

            $table->unsignedBigInteger('applied_by')->nullable()->comment('user_id');
            $table->foreign('applied_by')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName('mysql').'.users');

            $table->unsignedBigInteger('evaluated_by')->nullable()->comment('user_id');
            $table->foreign('evaluated_by')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName('mysql').'.users');

            $table->unsignedBigInteger('assessed_by')->nullable()->comment('user_id');
            $table->foreign('assessed_by')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName('mysql').'.users');

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
        Schema::connection('iskocab')->dropIfExists('scholar_has_evaluation_summaries');
    }
}
