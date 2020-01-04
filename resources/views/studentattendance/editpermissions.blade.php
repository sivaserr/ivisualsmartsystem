@extends('layout.app')


@section('content')
<div id="app">
@if(auth()->user()->GetRole() === 1 || auth()->user()->GetRole() === 3)
<student-attendance-edit-permissions :authenticateduser="{{ Auth::user() }}" :authrole="{{ Auth::user()->GetRole() }}"></student-attendance-edit-permissions>
@endif
</div>


@endsection