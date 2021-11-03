<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid19vaccine')->create('vaccinators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->unsignedBigInteger('health_facilities_id')->nullable();
            $table->foreign('health_facilities_id')
            ->nullable()
            ->constrained()
            ->references('id')->on('health_facilities');
            $table->string('prc_license_number')->nullable();
            $table->string('profession')->nullable();
            $table->string('role')->nullable();
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
        Schema::connection('covid19vaccine')->dropIfExists('vaccinators');
    }
}
