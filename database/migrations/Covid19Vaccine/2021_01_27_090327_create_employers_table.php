<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid19vaccine')->create('employers', function (Blueprint $table) {
            $table->bigIncrements('id');
            //connected to employment_status table
            $table->string('employment_status_id')->nullable();

            //connected to profession table
            $table->string('profession_id')->nullable();
            $table->string('specific_profession')->nullable();
            //
            $table->string('employer_name')->nullable();

            $table->string('employer_provice')->nullable()->comment('add defaul value on controller');
            $table->string('employer_city')->nullable()->comment('add defaul value on controller');

            //connected to barangay table
            $table->string('employer_barangay_id')->nullable();
            $table->string('employer_barangay_name')->nullable()->comment('save the barangay name');
            $table->string('employer_contact')->nullable();
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
        Schema::connection('covid19vaccine')->dropIfExists('employers');
    }
}
