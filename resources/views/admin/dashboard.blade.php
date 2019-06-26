@extends('layouts.app')

@section('content')
    <a href="">Admin Dashboard</a>

@hasanyrole('super admin|web admin')
    <a href="">Edit Web Pages</a>
    <a href="">Manage Surveys</a>
    <a href="">Manage Email Campaigns</a>
    <a href="">Manage Newsletters</a>
    <a href="">Manage Calendar of Events</a>
    <a href="">Manage Fundraising Campaigns</a>
@endhasanyrole
@hasanyrole('super admin|blog admin')
    <a href="">Manage Blog</a>
@endhasanyrole
@hasanyrole('super admin|forum admin')
    <a href="">Manage Forum</a>
@endhasanyrole
@hasanyrole('super admin|membership admin')
    <a href="">Manage User Accounts</a>
    <a href="">Manage User Permissions/Roles</a>
    <a href="">Manage User Profiles</a>
    <a href="">Manage User Blog Posts</a>
    <a href="">Manage User Volunteer Hours</a>
    <a href="">Approve Probationary Memberships</a>
    <a href="">Approve Full Membership</a>
@endhasanyrole
@hasanyrole('super admin|financial admin')
    <a href="">Manage Membership Dues</a>
    <a href="">Manage Shed Rental Dues</a>
    <a href="">Manage User Invoices</a>
    <a href="">Manage Payment Processing</a>
    <a href="">Manually Enter Cash / Checks</a>
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