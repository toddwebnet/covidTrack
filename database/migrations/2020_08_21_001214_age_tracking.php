<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgeTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('age_tracking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('as_of');
            $table->date('start_week');
            $table->date('end_week');
            $table->string('state');
            $table->string('sex');
            $table->string('age_group');
            $table->bigInteger('covid_deaths');
            $table->bigInteger('total_deaths');
            $table->bigInteger('pneumonia_deaths');
            $table->bigInteger('pneumonia_covid_deaths');
            $table->bigInteger('flu_deaths');
            $table->bigInteger('pneumonia_flu_covid_deaths');
            $table->string('footnote');
            $table->timestamps();
        });

        Schema::create('age_tracking_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('as_of');
            $table->date('start_week');
            $table->date('end_week');
            $table->string('state');
            $table->string('sex');
            $table->string('age_group');
            $table->bigInteger('covid_deaths');
            $table->bigInteger('total_deaths');
            $table->bigInteger('pneumonia_deaths');
            $table->bigInteger('pneumonia_covid_deaths');
            $table->bigInteger('flu_deaths');
            $table->bigInteger('pneumonia_flu_covid_deaths');
            $table->string('footnote');
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
        Schema::dropIfExists('age_tracking');
        Schema::dropIfExists('age_tracking_history');
    }
}
