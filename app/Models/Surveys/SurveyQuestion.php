<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
	protected $fillable = ['question', 'survey_id'];

	public function survey() {
		return $this->belongsTo(Survey::class);
	}

	public function answers() {
		return $this->hasMany(SurveyAnswer::class);
	}
}
