<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarHasSubjectGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('iskocab')->create('scholar_has_subject_grades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('grade_list')->nullabe()->comment('list of scholar grades');
            $table->string('gwa')->nullabe()->comment('list of scholar grades');
            $table->string('total_grade_equivalent')->nullabe();
            $table->string('overall_remarks')->nullabe();
            $table->string('status')->nullabe();
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
        Schema::connection('iskocab')->dropIfExists('scholar_has_subject_grades');
    }
}
