<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on(connectionName().'.users');
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('employment_status')->nullable();
            $table->string('employment_description')->nullable();
            $table->string('preferred_occupation')->nullable();
            $table->string('preferred_occupation_details')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('disable')->nullable();
            $table->string('disable_category')->nullable();
            $table->string('language_dialect')->nullable();
            $table->string('other_language')->nullable();
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
        Schema::dropIfExists('personal_information');
    }
}
