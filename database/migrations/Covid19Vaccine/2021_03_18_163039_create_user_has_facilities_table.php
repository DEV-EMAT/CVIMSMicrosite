<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHasFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid19vaccine')->create('user_has_facilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                    ->nullable()
                  ->constrained()
                  ->references('id')->on(connectionName().'.users');
            $table->unsignedBigInteger('facility_id');
            $table->foreign('facility_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('health_facilities');
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
        Schema::connection('covid19vaccine')->dropIfExists('user_has_facilities');
    }
}
