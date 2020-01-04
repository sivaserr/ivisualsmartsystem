@extends('layout.app')


@section('content')
<div id="app">
<student-attendance-remove-special :authenticateduser="{{ Auth::user() }}" :authrole="{{ Auth::user()->GetRole() }}"></student-attendance-remove-special>
</div>


@endsection