<?php

namespace App\Models\Surveys;

use App\Models\Members\User;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
	protected $fillable = ['title', 'description', 'ends_at'];
	protected $dates = ['ends_at'];

	public function questions() {
		return $this->hasMany(SurveyQuestion::class);
	}

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function answers() {
		return $this->hasMany(SurveyAnswer::class);
	}
}
