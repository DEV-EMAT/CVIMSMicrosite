<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid19vaccine')->create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category_format')->nullable()->comment('format- 01_Health_Care_Worker');
            $table->string('category_name')->nullable();
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
        Schema::connection('covid19vaccine')->dropIfExists('categories');
    }
}
