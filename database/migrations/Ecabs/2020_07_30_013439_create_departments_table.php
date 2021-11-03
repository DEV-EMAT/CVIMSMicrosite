<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('department')->nullable();
            $table->string('acronym')->nullable();
            $table->string('office_hours')->nullable();
            $table->longText('about')->nullable();
            $table->longText('mission')->nullable();
            $table->longText('vision')->nullable();
            $table->longText('mobile')->nullable();
            $table->longText('telephone')->nullable();
            $table->longText('email_address')->nullable();
            $table->longText('website')->nullable();
            $table->longText('address')->nullable();
            $table->unsignedBigInteger('barangay_id')->nullable();
            $table->foreign('barangay_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('barangays');
            $table->longText('employees')->nullable();
            $table->longText('logo')->nullable();
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
        Schema::dropIfExists('departments');
    }
}
