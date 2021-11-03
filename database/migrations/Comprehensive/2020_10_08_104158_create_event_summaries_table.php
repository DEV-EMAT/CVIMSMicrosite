<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('comprehensive')->create('event_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')                                                                                                                                                                                                                   
                  ->constrained()
                  ->references('id')->on('events')
                  ->onDelete('cascade');
            $table->longText('venue')->nullable();
            $table->string('date_of_event')->nullable();
            $table->string('time_of_event_from')->nullable();
            $table->string('time_of_event_to')->nullable();
            $table->string('time_in_allowance')->nullable();
            $table->string('time_out_allowance')->nullable();
            $table->integer('attendees_capacity')->nullable();
            $table->longText('reasons')->nullable();
            $table->string('required_attendance')->nullable();
            $table->string('exclusive')->nullable()->comment("(1 = exclusive, 0 = for all) Eto kung gusto mong makita ng lahat or pang department nyo lang.");
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
        Schema::connection('comprehensive')->dropIfExists('event_summaries');
    }
}
