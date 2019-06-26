<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvalidInvoiceWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invalid_invoice_webhooks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_id')->nullable();
            $table->string('reason')->nullable();
            $table->text('raw_stripe_data')->nullable();
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
        Schema::dropIfExists('invalid_invoice_webhooks');
    }
}
