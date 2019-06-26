@extends('layouts.app')

@section('content')
@hasanyrole('super admin|web admin|blog admin|forum admin|membership admin|financial admin')
    <a href="">Admin Dashboard</a>
@endhasanyrole
    <a href="">My Dashboard</a>
    <a href="">My Invoices</a>
    <a href="">Track Volunteer Hours</a>
    <a href="">My Member Profile</a>
    <a href="">My Blog</a>
    <a href="">Calendar of Events</a>
    <a href="">View Booster</a>
    <a href="">Pending Surveys / Polls</a>
    <a href="">Donations</a>

    <a href="">Member Forum</a>
@endsection