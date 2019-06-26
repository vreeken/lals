@extends('layouts.app')

@section('content')

    <h1>Volunteer Hours Tracking</h1>
    <div class="volunteer-hours-container">
        <volunteer-hours-chart :hours='@json($hours)'></volunteer-hours-chart>
        <volunteer-hours-form></volunteer-hours-form>
    </div>

@endsection

