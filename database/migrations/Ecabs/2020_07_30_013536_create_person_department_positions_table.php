<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonDepartmentPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('person_department_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('person_id')->nullable();
            $table->foreign('person_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('people');
            $table->unsignedBigInteger('department_position_id')->nullable();
            $table->foreign('department_position_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('department_positions');
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
        Schema::dropIfExists('person_department_positions');
    }
}
