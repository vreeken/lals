@extends('layouts.app')

@section('head')
	<script src="https://cdn.tiny.cloud/1/{{ env('TINY_MCE_API_KEY') }}/tinymce/5/tinymce.min.js"></script>

@endsection


@section('content')
	<calendar-event-form calendars='@json($calendars)'></calendar-event-form>
@endsection