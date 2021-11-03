<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarHasApplicationSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('iskocab')->create('scholar_has_application_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('application_id')->nullable()->comment('scholar_has_application_id');
            $table->foreign('application_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('scholar_has_applications');
                    
            $table->unsignedBigInteger('assistance_id')->nullable()->comment('assistance_type_id');
            $table->foreign('assistance_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('assistance_types');
                    
            $table->unsignedBigInteger('scholar_course_id')->nullable()->comment('scholar_has_course_summaries table');
            $table->foreign('scholar_course_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('scholar_has_course_summaries');
                    
            $table->unsignedBigInteger('scholar_school_id')->nullable()->comment('scholar_has_school_summaries table');
            $table->foreign('scholar_school_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('scholar_has_school_summaries');
                    
            $table->unsignedBigInteger('grades_id')->nullable()->comment('scholar_has_subject_grades (List of Grades)');
            $table->foreign('grades_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('scholar_has_subject_grades');

            $table->unsignedBigInteger('applied_by')->nullable()->comment('applied');
            $table->foreign('applied_by')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('ecabs_main_db.users');
                    
            $table->string('exam_qualification')->nullable()->comment('[QUALIFIED,UNQUALIFIED,EXEMPTION]');
            $table->longtext('exam_qualification_remarks')->nullable()->comment('QUALIFIED = null, QUALIFIED but EXEMP by EVALUATOR = Reason for exemption, UNQUALIFIED = "not required for exam" ');
            $table->string('exam_already_taken_status')->nullable()->comment('taken = 1, not taken = 0');
            $table->string('year_level')->nullable()->comment('e.g. FIRST YEAR, SECOND YEAR etc..');
            $table->string('status')->nullable()->comment('0 = false, 1 = active | get always the active data');
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
       Schema::connection('iskocab')->dropIfExists('scholar_has_application_summaries');
    }
}
