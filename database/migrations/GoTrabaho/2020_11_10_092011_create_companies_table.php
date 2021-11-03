<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on(connectionName().'.users');
            $table->string('company_name')->nullable();
            $table->string('company_accronym')->nullable();
            $table->string('tin_number')->nullable();
            $table->unsignedBigInteger('employer_type_id')->nullable();
            $table->foreign('employer_type_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on('employer_types');
            $table->string('total_work_force')->nullable();
            $table->string('line_of_business')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
