@extends('layouts.app')

@section('head')
	<style>
		.responsiveCal {
			position: relative;
			padding-bottom: 75%;
			height: 0;
			overflow: hidden;
			width: 100%;
		}

		.responsiveCal iframe {
			position: absolute;
			top:0;
			left: 0;
			width: 100%;
			height: 100%;
		}
	</style>
@endsection

@section('content')
	@if (Auth::guest())
		<calendar :calendars='@json($calendars)' :events='@json($events)'></calendar>
		<!--<div class="responsiveCal">
			<iframe src="https://calendar.google.com/calendar/b/2/embed?height=600&amp;wkst=1&amp;bgcolor=%23f5f8fa&amp;ctz=America%2FLos_Angeles&amp;src=dmZmNWVlazF0NTNtdG5sZHBtbTRkZmcxZjRAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&amp;color=%23E67C73&amp;title=Los%20Angeles%20Live%20Steamers%20Railroad%20Museum%20Public%20Calendar%20of%20Events" style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
		</div>-->
	@else
		<!--<div class="responsiveCal">
			<iframe src="https://calendar.google.com/calendar/b/2/embed?height=600&amp;wkst=1&amp;bgcolor=%23f5f8fa&amp;ctz=America%2FLos_Angeles&amp;src=cG1yMjhva2I1NnAwaHQ1NWE0cWh1OTN0ZnNAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&amp;src=dmZmNWVlazF0NTNtdG5sZHBtbTRkZmcxZjRAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&amp;color=%234285F4&amp;color=%23D50000&amp;title=LALSRM%20Public%20and%20Members%20Only%20Calendar" style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
		</div>-->

		<calendar :calendars='@json($calendars)' :events='@json($events)'></calendar>
	@endif
@endsection
