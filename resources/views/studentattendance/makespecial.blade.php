@extends('layout.app')


@section('content')
<div id="app">
<student-attendance-create-special :authenticateduser="{{ Auth::user() }}" :authrole="{{ Auth::user()->GetRole() }}"></student-attendance-create-special>
</div>


@endsection