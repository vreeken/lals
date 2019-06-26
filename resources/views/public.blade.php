@extends('layouts.app')

@section('title')
	{{ $page->title }}
@endsection

@section('content')
	{!! $page->content !!}

	@can('admin pages')
		<a href="/edit/{{ Request::path() }}">Edit this page</a>
	@endcan
@endsection