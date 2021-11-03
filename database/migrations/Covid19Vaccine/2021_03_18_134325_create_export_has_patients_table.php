<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportHasPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid19vaccine')->create('export_has_patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('export_summary_id')->nullable();
            $table->foreign('export_summary_id')
                  ->constrained()
                  ->nullable()
                  ->references('id')->on('export_summaries');
            $table->string('patient_id')->nullable();
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
        Schema::connection('covid19vaccine')->dropIfExists('export_has_patients');
    }
}
