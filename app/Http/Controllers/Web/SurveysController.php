<?php

namespace App\Http\Controllers\Web;

use App\Models\Surveys\Survey;
use App\Models\Surveys\SurveyQuestion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;

class SurveysController extends Controller
{
	public function showSurveys() {
		$surveys = Survey::where('ends_at', '>', Carbon::now())->orWhereNull('ends_at')->orderBy('created_at', 'ASC')->get();

		return view('surveys.show_all', ['surveys' => $surveys]);
	}

	public function showSurvey(Survey $survey) {
		$s['title'] = $survey->title;
		$s['description'] = $survey->description;
		$s['ends_at'] = $survey->ends_at;
		$s['questions'] = json_decode($survey->questions);

		return view('surveys.show_survey', ['survey' => $s]);
	}

	public function storeAnswer(Request $request) {

	}

	public function showCreateSurvey() {

		return view('surveys.show_create');
	}

	public function storeSurvey(Request $request) {
		$data = $request->only('title', 'questions', 'ends_at', 'description');

		$validator = Validator::make($data, [
			'title' => ['required', 'string', 'max:255'],
			'description' => ['string', 'max:512', 'nullable'],
			'ends_at' => ['date', 'after:now', 'nullable'],
			'questions' => ['required', 'json']
		]);

		if ($validator->fails()) {
			if ($request->expectsJson()) {
				return response()->json(['errors' => $validator->errors()], 422);
			}
			else {
				return redirect()->back()->withErrors($validator->errors());
			}
		}

		DB::beginTransaction();


		$survey = Survey::create([
			'title' => $data['title'],
			'description' => $data['description'],
			'ends_at' =>  $data['ends_at'] !== null ? Carbon::createFromFormat('Y-m-d H:i', $data['ends_at']) : null,
			'user_id' => Auth::user()->id
		]);

		if (!$survey) {
			DB::rollBack();
			return response()->json(['error'=>'db_error'], 500);
		}

		$questions = json_decode($data['questions']);
		$len = count($questions);
		$inserts = [];
		for ($i=0; $i<$len; $i++) {
			$q = $questions[$i];
			$inserts[] = [
				'survey_id' => $survey->id,
				'question' => $q->q,
				//1: text, 2: number, 3: float, 4: yes/no, 10: single_choice, 11: multiple_choice
				'question_type' => $q->t,
				'choices' => $q->t >= 10 ? json_encode($q->c) : null
			];
		}

		if (!SurveyQuestion::insert($inserts)) {
			DB::rollBack();
			return response()->json(['error'=>'db_error'], 500);
		}

		DB::commit();

		return response()->json(['success'=>'survey_created']);
	}
}
