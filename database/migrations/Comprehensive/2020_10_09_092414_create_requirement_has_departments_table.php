<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequirementHasDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::connection('comprehensive')->create('requirement_has_departments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('requirement_id');
            $table->foreign('requirement_id')
                ->nullable()
                ->constrained()
                ->references('id')->on('requirements');
            $table->unsignedBigInteger('department_id');
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
         Schema::connection('comprehensive')->dropIfExists('requirement_has_departments');
    }
}
