<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationalAttainmentHasRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('iskocab')->create('educational_attainment_has_requirements', function (Blueprint $table) {
            $table->bigIncrements('id');
            //[start]from ecabs program services 
            $table->unsignedBigInteger('program_id')->nullable()->comment('program services id');
            $table->foreign('program_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName('comprehensive').'.program_services');
            //[end] from ecabs program services
            $table->unsignedBigInteger('ea_id')->nullable()->comment('educational attainment id');
            $table->foreign('ea_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('educational_attainments');
            //[start]from ecabs requirements 
            $table->unsignedBigInteger('requirement_id')->nullable()->comment('requirments id from ecabs db');
            $table->foreign('requirement_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName('comprehensive').'.requirements');
            //[end] from ecabs requirements 
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
        Schema::connection('iskocab')->dropIfExists('educational_attainment_has_requirements');
    }
}
