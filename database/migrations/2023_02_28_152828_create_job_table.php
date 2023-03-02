<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->unique()->nullable();
            $table->string('job_title');
            $table->integer('client_id')->unsigned();
            $table->string('job_description');
            $table->integer('job_status')->unsigned();
            $table->integer('job_type')->unsigned();
            $table->integer('job_category')->unsigned();
            $table->string('job_location');
            $table->date('job_start_date')->date_format('d-m-Y');
            $table->date('job_end_date')->date_format('d-m-Y');
            $table->time('job_start_time');
            $table->time('job_end_time');
            $table->string('job_salary');
            $table->time('break_time');
            $table->time('admin_time');
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
        Schema::dropIfExists('jobs');
    }
}
