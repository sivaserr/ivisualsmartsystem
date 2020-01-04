@extends('layout.app')


@section('content')

<div id="app">
    <student-attendance-viewbymonth-count :authenticateduser="{{ Auth::user() }}" :authrole="{{ Auth::user()->GetRole() }}"></student-attendance-viewbymonth-count>
</div>

@endsection