<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Web\Calendar;
use App\Models\Web\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CalendarController extends Controller
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
        //
    }


	public function show(Request $request) {
		//In order for members to view the members only calendar they have to be invited (manual invitations only?)
		//Might want to look up a better way

		//Leaning toward this:
		//https://github.com/maddhatter/laravel-fullcalendar - laravel models, fullcalendar.io frontend
		//Probably in conjunction with this:
		//https://www.npmjs.com/package/vue-full-calendar - vue/fullcalendar.io frontend, needs backend

		//https://packagist.org/packages/spatie/laravel-google-calendar - interact with google calendar

		$permission = 0;
		if (Auth::check()) {
			if (Auth::user()->hasAnyRole('super admin', 'web admin', 'blog admin', 'forum admin', 'membership admin', 'financial admin')) {
				$permission = 2;
			}
			else {
				$permission = 1;
			}
		}


		$calendars = Calendar::where('permission', '<=', $permission)->get(['id', 'title', 'permission']);
		$cids = $calendars->pluck('id');
		$events = CalendarEvent::whereIn('calendar_id', $cids)->get(['calendar_id', 'starts_at', 'ends_at', 'title']);

		return view('web.calendar', ['calendars' => $calendars, 'events' => $events]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function edit(Calendar $calendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calendar $calendar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar)
    {
        //
    }
}
