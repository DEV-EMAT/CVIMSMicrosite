<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssistanceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('iskocab')->create('assistance_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullabe();
            //[start]from ecabs program services 
            $table->unsignedBigInteger('program_services_id')->nullable();
            $table->foreign('program_services_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName('comprehensive').'.program_services');
            //[end] from ecabs program services
            $table->unsignedBigInteger('educational_attainment_id')->nullable();
            $table->foreign('educational_attainment_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('educational_attainments');
            $table->string('grade_from')->nullabe();
            $table->string('grade_to')->nullabe();
            $table->string('required_exam')->nullabe()->comment('[0 not required | 1 required]');
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
        Schema::connection('iskocab')->dropIfExists('assistance_types');
    }
}
