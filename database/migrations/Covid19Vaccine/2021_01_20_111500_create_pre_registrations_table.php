<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid19vaccine')->create('pre_registrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('registration_code')->nullable();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('sex')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('civil_status')->nullable();
            //connected to employer table
            $table->string('employment_id')->nullable()->comment('if applicable, if not add defaul value');
            //
            $table->string('province')->nullable()->comment('add defaul value on controller');
            $table->string('city')->nullable()->comment('add defaul value on controller');
            $table->string('barangay')->nullable()->comment('save the barangay name');
            $table->string('barangay_id')->nullable();
            $table->string('category_id')->nullable();
            $table->string('category_id_number')->nullable()->comment('number of selected ID depending on category type (string type)');
            $table->string('philhealth_number')->nullable();
            $table->longText('home_address')->nullable();
            $table->longText('image')->nullable();
            $table->string('category_for_id')->nullable();
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
        Schema::connection('covid19vaccine')->dropIfExists('pre_registrations');
    }
}
