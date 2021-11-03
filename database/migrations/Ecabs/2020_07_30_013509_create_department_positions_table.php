<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('department_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('departments');
            $table->unsignedBigInteger('position_access_id')->nullable();
            $table->foreign('position_access_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('position_accesses');
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
        Schema::dropIfExists('department_positions');
    }
}
