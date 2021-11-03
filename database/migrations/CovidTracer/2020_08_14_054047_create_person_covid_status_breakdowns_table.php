<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonCovidStatusBreakdownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid_tracer')->create('person_covid_status_breakdowns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('person_covid_status_id');
            $table->foreign('person_covid_status_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('person_covid_statuses');
            $table->integer('user_1_id')->nullable();
            $table->longtext('user_1_status_breakdown')->nullable();
            $table->integer('identifier')->nullable();
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
        Schema::connection('covid_tracer')->dropIfExists('person_covid_status_breakdowns');
    }
}
