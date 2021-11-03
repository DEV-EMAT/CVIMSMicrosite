<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstablishmentInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //protected $connection = 'covid_tracer_db';
    public function up()
    {
        Schema::connection('covid_tracer')->create('establishment_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('establishment_category_id');
            $table->foreign('establishment_category_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('establishment_categories');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->foreign('owner_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName().'.users')
                    ->comment('get user id from main db');
            $table->string('establishment_identification_code')
                    ->nullable()
                    ->comment('QR or Barcode');
            $table->string('business_name')->nullable();
            $table->string('business_permit_number')->nullable();
            $table->longText('address')->nullable();
            $table->unsignedBigInteger('barangay_id')->nullable();
            $table->foreign('barangay_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName().'.barangays');
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
        Schema::connection('covid_tracer')->dropIfExists('establishment_information');
    }
}
