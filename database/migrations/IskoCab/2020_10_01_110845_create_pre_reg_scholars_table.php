<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreRegScholarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('iskocab')->create('pre_reg_scholars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName().'.users');
            $table->integer('school_id')->nullable();
            $table->string('school_name')->nullable();
            $table->string('image')->nullable();
            $table->integer('course_id')->nullable();
            $table->string('course')->nullable();
            $table->string('pre_registration_status')->comment("VERIFIED/UNVERIFIED P.S. word dapat")->nullable();
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
        Schema::connection('iskocab')->dropIfExists('pre_reg_scholars');
    }
}
