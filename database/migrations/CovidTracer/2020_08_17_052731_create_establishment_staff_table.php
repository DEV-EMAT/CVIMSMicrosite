<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstablishmentStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::connection('covid_tracer')->create('establishment_staff', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('establishment_information_id')->nullable();
            $table->foreign('establishment_information_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('establishment_information');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName().'.users');
            $table->string('start')->nullable();
            $table->string('end')->nullable();
            $table->string('staff_status');
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
        Schema::connection('covid_tracer')->dropIfExists('establishment_staff');
    }
}
