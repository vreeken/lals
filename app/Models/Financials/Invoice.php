<?php

namespace App\Models\Financials;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{

	protected $fillable = ['stripe_invoice_id', 'user_id', 'status', 'due_date', 'amount_due', 'amount_paid', 'invoice_pdf', 'attempt_count', 'raw_stripe_data'];


}
