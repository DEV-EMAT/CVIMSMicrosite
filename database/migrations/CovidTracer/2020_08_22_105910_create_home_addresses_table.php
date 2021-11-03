<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid_tracer')->create('home_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('patient_profile_id')->nullable();
            $table->foreign('patient_profile_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('patient_profiles');
            $table->string('house_no')->nullable();
            $table->string('street')->nullable();
            $table->string('city_municipality')->nullable();
            $table->string('province_state')->nullable();
            $table->string('region_country')->nullable();
            $table->string('barangay')->nullable();
            $table->string('home_office_no')->nullable();
            $table->string('cellphone_no')->nullable();
            $table->string('category')->nullable();
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
        Schema::connection('covid_tracer')->dropIfExists('home_addresses');
    }
}
