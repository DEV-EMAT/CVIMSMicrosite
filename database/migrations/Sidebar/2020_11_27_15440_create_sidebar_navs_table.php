<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSidebarNavsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sidebar')->create('sidebar_navs', function (Blueprint $table) {
            $table->bigIncrements('id');
            //link of the mamangement
            $table->string('route')->nullable();
            //font of the button
            $table->string('font_icon')->nullable();
            //title of the button
            $table->string('title')->nullable();
            //link of dropdown
           $table->string('collapse_href')->nullable();
           //title of the button
           $table->string('permision_list')->nullable();
           
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
        Schema::connection('sidebar')->dropIfExists('sidebar_navs');
    }
}
