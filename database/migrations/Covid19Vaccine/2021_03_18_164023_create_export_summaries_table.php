<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('covid19vaccine')->create('export_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('datetime_requested')->nullable();
            $table->string('export_type')->nullable();
            $table->unsignedBigInteger('user_has_facilities_id');
            $table->foreign('user_has_facilities_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on('user_has_facilities');
            $table->longText('generated_by')->nullable();
            $table->string('remarks')->comment('optional')->nullable();
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
        Schema::connection('covid19vaccine')->dropIfExists('export_summaries');
    }
}
