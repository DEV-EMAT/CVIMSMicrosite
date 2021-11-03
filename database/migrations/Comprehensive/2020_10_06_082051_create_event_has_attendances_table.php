<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventHasAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('comprehensive')->create('event_has_attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('attendances_id')->nullable();
            $table->foreign('attendances_id')
                  ->constrained()
                  ->nullable()
                  ->references('id')->on('attendances');
            $table->string('attendees')->nullable();
            $table->string('person_code')->nullable();
            $table->string('user_id')->nullable();
            $table->string('attendee_status')->nullable();
            $table->string('attendee_remarks')->nullable();
            $table->string('time_in')->nullable();
            $table->string('time_out')->nullable();
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
        Schema::connection('comprehensive')->dropIfExists('event_has_attendances');
    }
}
