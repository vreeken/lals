<?php

namespace App\Models\Surveys;

use App\Models\Members\User;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
	protected $fillable = ['survey_id', 'survey_question_id', 'answer'];

	public function survey() {
		return $this->belongsTo(Survey::class);
	}

	public function question() {
		return $this->belongsTo(SurveyQuestion::class);
	}

	public function user() {
		return $this->belongsTo(User::class);
	}
}
