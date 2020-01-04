@extends('layout.app')


@section('content')

<div id="app">
    <home-page :authenticateduser="{{ Auth::user() }}" basepath="{{ url('/') }}"></home-page>
    @if(auth()->user()->GetRole() === 1 || auth()->user()->GetRole() === 3 || auth()->user()->GetRole() === 4)
    <staff-myschedule :authenticateduser="{{ Auth::user() }}"></staff-myschedule>
    @endif
</div>

@endsection