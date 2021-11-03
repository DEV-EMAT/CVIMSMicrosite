<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualifiedPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid19vaccine')->create('qualified_patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('registration_id')->nullable();
            $table->foreign('registration_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('pre_registrations');
            $table->string('qrcode')->nullable();
            $table->string('qualification_status')->nullable();
            $table->string('verified_by')->nullable();
            $table->string('assessment_status')->nullable();
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
        Schema::connection('covid19vaccine')->dropIfExists('qualified_patients');
    }
}
