<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationalAttainmentHasRequiredEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('iskocab')->create('educational_attainment_has_required_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ea_id')->nullable()->comment('educational attainment id');
            $table->foreign('ea_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('educational_attainments');
            //[start]from ecabs program services 
            $table->unsignedBigInteger('program_id')->nullable()->comment('program id');
            $table->foreign('program_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName('comprehensive').'.program_services');
            //[end] from ecabs program services
            //[start]from ecabs program services 
            $table->unsignedBigInteger('event_id')->nullable()->comment('event id from ecabs db');
            $table->foreign('event_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on(connectionName('comprehensive').'.events');
            //[end] from ecabs program services
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
        Schema::connection('iskocab')->dropIfExists('educational_attainment_has_required_events');
    }
}
