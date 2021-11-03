<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\helper;

class CreateCovidTracersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //protected $connection = 'covid_tracer_db';
    public function up()
    {
        Schema::connection('covid_tracer')->create('covid_tracers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('temperature')->nullable();
            $table->longtext('transaction_one')->nullable()
                    ->comment('sino nag scan');
            $table->longtext('transaction_two')->nullable()
                    ->comment('sino ang iniscan');
            $table->integer('type')->nullable()
                    ->comment('1-EP, 2-PE, 3-PP (E=Establishment, P=Person)');
            $table->longText('location')->nullable()
                    ->comment('get location coordinate via google map');
            $table->timestamp('date_created')->nullable();
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
        Schema::connection('covid_tracer')->dropIfExists('covid_tracers');
    }
}
