@extends('layouts.app')

@section('content')

    <h1>My Invoices</h1>

    <invoices :invoices='@json(Auth::user()->invoices)'></invoices>

@endsection

