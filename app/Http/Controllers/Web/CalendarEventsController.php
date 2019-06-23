<?php

namespace App\Http\Controllers\Web;

use App\Models\Web\Calendar;
use App\Models\Web\CalendarEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CalendarEventsController extends Controller
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
        //https://developers.google.com/calendar/v3/reference/events/insert
		//https://developers.google.com/calendar/create-events
		$calendars = Calendar::all();
		return view('admin.web.create_calendar_event', ['calendars'=>$calendars]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$data = $request->only('calendar_id', 'title', 'description', 'url', 'starts_at', 'ends_at');

		$validator = Validator::make($data, [
			'calendar_id' => ['integer', 'exists:calendars,id'],
			'title' => ['required', 'string', 'max:255'],
			'description' => ['string', 'max:255', 'nullable'],
			'url' => ['string', 'max:255', 'nullable', 'active_url'],
			'starts_at' => ['required', 'date'],
			'ends_at' => ['required', 'date', 'after:starts_at'],
		]);

		if ($validator->fails()) {
			if ($request->expectsJson()) {
				return response()->json(['errors' => $validator->errors()], 422);
			}
			else {
				return redirect()->back()->withErrors($validator->errors());
			}
		}

		$data['starts_at'] = \DateTime::createFromFormat('Y-m-d H:i', $data['starts_at']);
		$data['ends_at'] = \DateTime::createFromFormat('Y-m-d H:i', $data['ends_at']);

		$data['user_id'] = Auth::user()->id;

		$cal_event = CalendarEvent::create($data);

		if (!$cal_event) {
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
     * @param  \App\CalendarEvent  $calendarEvent
     * @return \Illuminate\Http\Response
     */
    public function show(CalendarEvent $calendarEvent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CalendarEvent  $calendarEvent
     * @return \Illuminate\Http\Response
     */
    public function edit(CalendarEvent $calendarEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CalendarEvent  $calendarEvent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CalendarEvent  $calendarEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy(CalendarEvent $calendarEvent)
    {
        //
    }
}
