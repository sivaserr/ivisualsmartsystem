@extends('layout.app')


@section('content')

<div id="app">
@if(auth()->user()->GetRole() === 1)
    <academic-page :authenticateduser="{{ Auth::user() }}"></academic-page>
@endif
</div>

@endsection