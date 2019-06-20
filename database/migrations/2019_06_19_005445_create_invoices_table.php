<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('stripe_invoice_id');
            $table->string('invoice_number');
            $table->unsignedBigInteger('user_id');
            $table->string('status');
            $table->dateTime('due_date');
			$table->unsignedBigInteger("amount_due");
			$table->unsignedBigInteger("amount_paid");
			$table->string('invoice_pdf')->nullable();
			$table->string('receipt_url')->nullable();
			$table->unsignedTinyInteger('failed_attempt_count');
			$table->text('raw_stripe_create_data');
			$table->text('raw_stripe_webhook_data')->nullable();
			$table->dateTime('paid_at')->nullable();
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
