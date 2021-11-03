<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientRootsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid_tracer')->create('patient_roots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('patient_profile_id')->nullable();
            $table->foreign('patient_profile_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('patient_profiles');
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
        Schema::connection('covid_tracer')->dropIfExists('patient_roots');
    }
}
