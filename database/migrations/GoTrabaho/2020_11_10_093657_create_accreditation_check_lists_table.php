<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccreditationCheckListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accreditation_check_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_contact_id')->nullable();
            $table->foreign('company_contact_id')
                     ->nullable()
                     ->constrained()
                     ->references('id')->on('company_contacts');
            $table->unsignedBigInteger('requirements_id')->nullable();
            $table->foreign('requirements_id')
                      ->nullable()
                      ->constrained()
                      ->references('id')->on(connectionName('comprehensive').'.requirement_has_departments');
            $table->string('remarks')->nullable();
            $table->string('submitted')->nullable();
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
        Schema::dropIfExists('accreditation_check_lists');
    }
}
