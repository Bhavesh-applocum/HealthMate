<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsInJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->integer('job_status')->unsigned()->default(0)->after('ref_no');
            // $table->integer('visits')->after('break_time');
            $table->boolean('parking')->default(0)->after('break_time');
            $table->double('unit')->unsigned()->after('job_salary');
            // $table->integer('meals');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            //
        });
    }
}
