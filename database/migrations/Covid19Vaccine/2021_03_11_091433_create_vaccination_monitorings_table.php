<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinationMonitoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid19vaccine')->create('vaccination_monitorings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('qualified_patient_id')->nullable();
            $table->foreign('qualified_patient_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('qualified_patients');
            $table->string('dosage')->nullable();
            $table->string('vaccination_date')->nullable();
            $table->unsignedBigInteger('vaccine_category_id')->nullable();
            $table->foreign('vaccine_category_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('vaccine_categories');
            $table->string('batch_number')->nullable();
            $table->string('lot_number')->nullable();
            $table->unsignedBigInteger('vaccinator_id')->nullable();
            $table->foreign('vaccinator_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('vaccinators');
            $table->longText('consent')->nullable();
            $table->longText('reason_for_update')->nullable();
            $table->longText('reason_for_refusal')->nullable();
            $table->longText('deferral')->nullable();
            $table->string('encoded_by')->nullable();
            $table->string('verified_by')->nullable();
            $table->string('status')->nullable();
            $table->string('assessment_status')->nullable();
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
        Schema::connection('covid19vaccine')->dropIfExists('vaccination_monitorings');
    }
}
