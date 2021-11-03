<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesUpdateSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid_tracer')->create('cases_update_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('barangay_id')->nullable();
            $table->foreign('barangay_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName().'.barangays');
            $table->string('new_cases')->nullable();
            $table->string('active_cases')->nullable();
            $table->string('confirmed_cases')->nullable();
            $table->string('recovered')->nullable();
            $table->string('deceased')->nullable();
            $table->string('suspected_cases')->nullable();
            $table->string('bjmp_confirmed_cases')->nullable();
            $table->string('probable_cases')->nullable();
            $table->integer('identifier')->nullable();
            $table->string('status')->nullable();
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
        Schema::connection('covid_tracer')->dropIfExists('cases_update_summaries');
    }
}
