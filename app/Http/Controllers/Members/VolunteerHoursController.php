<?php

namespace App\Http\Controllers\Members;

use App\Models\Members\VolunteerHour;
use App\Models\Web\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Validator;

class VolunteerHoursController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$data = $request->only('hours', 'date_worked');

		$err_messages = ['before' => 'The :attribute cannot be a future date'];

		$validator = Validator::make($data, [
			'hours' => ['required', 'integer'],
			'date_worked' => ['required', 'date', 'before:tomorrow']
		], $err_messages);

		if ($validator->fails()) {
			if ($request->expectsJson()) {
				return response()->json(['errors' => $validator->errors()], 422);
			}
			else {
				return redirect()->back()->withErrors($validator->errors());
			}
		}

		$data['user_id'] = Auth::user()->id;

		$vh = VolunteerHour::create($data);

		if (!$vh) {
			if ($request->expectsJson()) {
				return response()->json(['error' => 'db_error'], 500);
			}
			else {
				return redirect()->back()->withErrors(['db_error'=>'Failed to save event. Please try again.']);
			}
		}

		if ($request->expectsJson()) {
			return response()->json(['success' => 'event_created']);
		}
		else {
			return redirect()->back()->with('success', 'Event Created');
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VolunteerHour  $volunteerHour
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
    	$hours = Auth::user()->volunteerHours()->where('created_at', '>=', Carbon::parse('first day of january'))->orderBy('date_worked', 'ASC')->get(['date_worked', 'hours']);
        return view('members.volunteer_hours', ['hours'=>$hours]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VolunteerHour  $volunteerHour
     * @return \Illuminate\Http\Response
     */
    public function edit(VolunteerHour $volunteerHour)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VolunteerHour  $volunteerHour
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VolunteerHour $volunteerHour)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VolunteerHour  $volunteerHour
     * @return \Illuminate\Http\Response
     */
    public function destroy(VolunteerHour $volunteerHour)
    {
        //
    }
}
