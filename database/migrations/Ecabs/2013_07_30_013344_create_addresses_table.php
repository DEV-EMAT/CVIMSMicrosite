<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('region')->nullable();
            $table->string('region_id')->nullable();
            $table->string('barangay')->nullable();
            $table->unsignedBigInteger('barangay_id')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
