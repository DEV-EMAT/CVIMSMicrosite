<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('iskocab')->create('scholars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName().'.users');
                    
            // $table->unsignedBigInteger('schoolsummary_id')->nullable();
            // $table->foreign('schoolsummary_id')
            //         ->nullable()
            //         ->constrained()
            //         ->references('id')->on('scholar_has_school_summaries');
                    
            // $table->unsignedBigInteger('course_id')->nullable();
            // $table->foreign('course_id')
            //         ->nullable()
            //         ->constrained()
            //         ->references('id')->on('courses');
            
            $table->string('image')->nullable();
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
        Schema::connection('iskocab')->dropIfExists('scholars');
    }
}
