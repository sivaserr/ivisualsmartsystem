@extends('layout.app')


@section('content')
<div id="app">
<student-attendance-viewbyprincipal :authenticateduser="{{ Auth::user() }}" :authrole="{{ Auth::user()->GetRole() }}"></student-attendance-viewbyprincipal>
</div>


@endsection