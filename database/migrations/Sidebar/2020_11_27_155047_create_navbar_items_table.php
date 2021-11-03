<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavbarItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sidebar')->create('navbar_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sidebar_id')->nullable();
            $table->foreign('sidebar_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName('sidebar').'.sidebar_navs');
            //link of the mamangement
            $table->string('href')->nullable();
            //font of the button
            $table->string('font_icon')->nullable();
            //title of the button
            $table->string('title')->nullable();
            //title of the button
            $table->string('permision_list')->nullable();
            
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
        Schema::connection('sidebar')->dropIfExists('navbar_items');
    }
}
