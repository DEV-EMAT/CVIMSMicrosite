<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarHasApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::connection('iskocab')->create('scholar_has_applications', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('progmodule_id')->nullable()->comment('scholarship_program_modules');
            $table->foreign('progmodule_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on('scholarship_program_modules');

            $table->unsignedBigInteger('scholar_id')->nullable();
            $table->foreign('scholar_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('scholars');
            $table->string('application_code')->nullable()->comment('format .e.g APP202000000001'); 
            $table->string('assessment_status')->nullable()->comment('TRUE = for assessment , FALSE (default) = pending or not allowed');
            $table->string('evaluation_status')->nullable()->comment('TRUE = for evaluated , FALSE (default) = unevaluated');        
            $table->string('application_status')->nullable()->comment('SUCCESS, FAILED');
            // $table->unsignedBigInteger('program_services_id')->nullable();
            // $table->foreign('program_services_id')
            //         ->nullable()
            //         ->constrained()
            //         ->references('id')->on(connectionName('comprehensive').'.program_services_has_departments');
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
       Schema::connection('iskocab')->dropIfExists('scholar_has_applications');
    }
}
