<?php

namespace App\Http\Controllers\Web;

use App\Models\Surveys\Survey;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SurveysController extends Controller
{
	public function showSurveys() {
		$surveys = Survey::where('ends_at', '>', Carbon::now())->get();

		return view('surveys.show_all', ['surveys' => $surveys]);
	}

	public function showSurvey(Survey $survey) {

		return view('surveys.show_survey', ['survey' => $survey]);
	}

	public function storeAnswer(Request $request) {

	}

	public function showCreateSurvey() {

		return view('surveys.show_create');
	}

	public function storeSurvey(Request $request) {

	}
}
