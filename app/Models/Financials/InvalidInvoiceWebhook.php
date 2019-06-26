<?php

namespace App\Models\Financials;

use Illuminate\Database\Eloquent\Model;

class InvalidInvoiceWebhook extends Model
{
	protected $fillable = ['invoice_id', 'reason', 'raw_stripe_data'];
}
