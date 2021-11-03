<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid_tracer')->create('print_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('barcode')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('module_printed')->nullable();
            $table->foreign('user_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName().'.users');
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
        Schema::connection('covid_tracer')->dropIfExists('print_documents');
    }
}
