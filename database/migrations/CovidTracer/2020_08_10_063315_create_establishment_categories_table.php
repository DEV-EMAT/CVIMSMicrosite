<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstablishmentCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //protected $connection = 'mysql2';
    public function up()
    {
        Schema::connection('covid_tracer')->create('establishment_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description')->nullable();
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
        Schema::connection('covid_tracer')->dropIfExists('establishment_categories');
    }
}
