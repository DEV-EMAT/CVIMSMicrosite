<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergencyResponseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('emergencyresponse')->create('emergency_response_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName().'.users');
            $table->unsignedBigInteger('incidentcat_id')->nullable();
            $table->foreign('incidentcat_id')
                    ->nullable()
                    ->constrained()
                    ->references('id')->on(connectionName('emergencyresponse').'.incident_categories');
            $table->longText('remarks')->nullable();
            $table->string('contact_number')->nullable();
            $table->longText('incident_location')->nullable();
            $table->string('incident_status')->nullable()->comment('Alarming, Received, Responsed, Resolved');
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
        Schema::connection('emergencyresponse')->dropIfExists('emergency_response_requests');
    }
}
