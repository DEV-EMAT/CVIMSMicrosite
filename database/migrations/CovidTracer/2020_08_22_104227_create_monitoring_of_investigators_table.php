<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoringOfInvestigatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid_tracer')->create('monitoring_of_investigators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('investigator_id')->nullable();
            $table->foreign('investigator_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('investigators');
            $table->string('mode_of_transportation')->nullable();
            $table->longText('places_of_engagement')->nullable();
            $table->string('remarks')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
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
        Schema::connection('covid_tracer')->dropIfExists('monitoring_of_investigators');
    }
}
