<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::connection('comprehensive')->create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('event_code')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')
                  ->constrained()
                  ->references('id')->on(connectionName().'.departments');
            $table->string('in_out_status')->nullable();
            $table->string('event_status')->comment('close or open')->nullable();
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
       Schema::connection('comprehensive')->dropIfExists('events');
    }
}
