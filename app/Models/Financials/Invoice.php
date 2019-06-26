<?php

namespace App\Models\Financials;

use App\Models\Members\User;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	protected $fillable = ['stripe_invoice_id', 'invoice_number', 'user_id', 'status', 'due_date', 'amount_due', 'amount_paid', 'invoice_pdf', 'receipt_url', 'failed_attempt_count', 'raw_stripe_create_data', 'raw_stripe_webhook_data'];

	protected $dates = ['created_at', 'updated_at', 'paid_at'];

	public function user() {
		return $this->belongsTo(User::class);
	}
}

