<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDeletionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_deletion_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')
                    ->constrained()
                    ->references('id')->on('users');
            $table->unsignedBigInteger('updated_status_user_id')->nullable();
            $table->foreign('updated_status_user_id')
                    ->constrained()
                    ->references('id')->on('users');
            $table->longtext('reason')->nullable();
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
        Schema::dropIfExists('user_deletion_histories');
    }
}
