<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateAccountDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('update_account_departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('update_id');
            $table->foreign('update_id')
                    ->constrained()
                    ->references('id')->on('updates');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                    ->constrained()
                    ->references('id')->on('users');
            $table->integer('merging_dept_id')->nullable();
            $table->string('status')
                    ->comment('default 0 = false if no merging department, 1 = true for merging department');
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
        Schema::dropIfExists('update_acount_departments');
    }
}
