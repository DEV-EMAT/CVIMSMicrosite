<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecimentInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid_tracer')->create('speciment_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('patient_profile_id')->nullable();
            $table->foreign('patient_profile_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('patient_profiles');
            $table->unsignedBigInteger('place_of_assignments_id')->nullable();
            $table->foreign('place_of_assignments_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('place_of_assignments');
            $table->string('speciment_category')->nullable();
            $table->string('date_collected')->nullable();
            $table->string('date_sent_to_RITM')->nullable();
            $table->string('date_received_in_RITM')->nullable();
            $table->string('virus_isolation_result')->nullable();
            $table->string('pcr_result')->nullable();
            $table->string('specimen_information_status')->nullable();
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
        Schema::connection('covid_tracer')->dropIfExists('speciment_information');
    }
}
