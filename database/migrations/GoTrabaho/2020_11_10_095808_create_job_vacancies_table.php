<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('gotrabaho')->create('job_vacancies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_contacts_id')->nullable();
            $table->foreign('company_contacts_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on('company_contacts');
            $table->string('job_title')->nullable();
            $table->string('employment')->nullable();
            $table->string('type_of_employment')->nullable();
            $table->string('preferred_sex')->nullable();
            $table->string('contract_type')->nullable();
            $table->string('contract_duration')->nullable();
            $table->longText('job_schedule')->nullable();
            $table->string('planned_start')->nullable();
            $table->string('planned_start_date')->nullable();
            $table->string('work_remote')->nullable();
            $table->string('total_work_force')->nullable();
            $table->string('salary_type')->nullable();
            $table->longText('salary')->nullable();
            $table->longText('job_description')->nullable();
            $table->longText('candidate_experience')->nullable();
            $table->longText('candidate_education')->nullable();
            $table->longText('candidate_location')->nullable();
            $table->longText('candidate_language')->nullable();
            $table->longText('candidate_eligibility')->nullable();
            $table->longText('requirements')->nullable();
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
        Schema::dropIfExists('job_vacancies');
    }
}
