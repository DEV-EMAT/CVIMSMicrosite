<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaceOfAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid_tracer')->create('place_of_assignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('barangay_id')->nullable();
            $table->foreign('barangay_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName().'.barangays');
            $table->unsignedBigInteger('investigator_id')->nullable();
            $table->foreign('investigator_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('investigators');
            $table->longText('description')->nullable();
            $table->string('investigator_category')->nullable();
            $table->string('assignment_status')->nullable();
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
        Schema::connection('covid_tracer')->dropIfExists('place_of_assignments');
    }
}
