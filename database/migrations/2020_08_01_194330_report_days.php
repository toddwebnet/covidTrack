<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReportDays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_days', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('state_id');
            $table->unsignedInteger('county_id');
            $table->bigInteger('cases')->nullable();
            $table->bigInteger('cases_delta')->nullable();
            $table->bigInteger('deaths')->nullable();
            $table->bigInteger('deaths_delta')->nullable();
            $table->date('report_date');
            $table->timestamps();
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('county_id')->references('id')->on('counties');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_days');
    }
}
