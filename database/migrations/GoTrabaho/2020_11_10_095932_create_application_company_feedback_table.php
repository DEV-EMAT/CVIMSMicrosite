<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationCompanyFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_company_feedback', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_application_id')->nullable();
            $table->foreign('job_application_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on('job_applications');
            $table->unsignedBigInteger('company_contact_id')->nullable();
            $table->foreign('company_contact_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on('company_contacts');
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
        Schema::dropIfExists('application_company_feedback');
    }
}
