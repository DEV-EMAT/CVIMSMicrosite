<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminationHasDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('comprehensive')->create('examination_has_departments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('exam_title_id')->nullable();
            $table->foreign('exam_title_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('exam_titles');

            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName().'.departments');
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
        Schema::connection('comprehensive')->dropIfExists('examination_has_departments');
    }
}
