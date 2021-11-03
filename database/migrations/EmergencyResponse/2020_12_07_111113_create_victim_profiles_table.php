<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVictimProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::connection('emergencyresponse')->create('victim_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('affiliation')->nullable();
            $table->string('gender')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->longText('address')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('telephone_number')->nullable();
            $table->string('religion')->nullable();
            $table->string('region')->nullable();
            $table->string('provice')->nullable();
            $table->string('city')->nullable();
            $table->string('barangay')->nullable();
            $table->string('home_address')->nullable();
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
         Schema::connection('emergencyresponse')->dropIfExists('victim_profiles');
    }
}
