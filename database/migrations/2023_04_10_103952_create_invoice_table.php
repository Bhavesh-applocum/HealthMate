<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('job_id')->unsigned();
            $table->bigInteger('candidate_id')->unsigned();
            $table->bigInteger('application_id')->unsigned();
            $table->bigInteger('timesheet_id')->unsigned();
            $table->string('invoice_amount')->nullable();
            $table->integer('invoice_status')->default(0);
            $table->date('invoice_date')->default(date('Y-m-d'));
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
        Schema::dropIfExists('invoices');
    }
}
