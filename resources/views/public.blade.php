@extends('layouts.app')

@section('content')
	{!! $page !!}

	@can('admin pages')
		<a href="/edit/{{ Request::path() }}">Edit this page</a>
	@endcan
@endsection