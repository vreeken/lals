<?php

namespace App\Models\Financials;

use Illuminate\Database\Eloquent\Model;

class InvalidInvoiceWebhook extends Model
{
	protected $fillable = ['invoice_id', 'raw_stripe_data'];
}
