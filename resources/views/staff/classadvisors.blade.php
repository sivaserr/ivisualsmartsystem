
@extends('layout.app')


@section('content')
<div id="app">
<staff-classadvisor :authenticateduser="{{ Auth::user() }}" :authrole="{{ Auth::user()->GetRole() }}"></staff-classadvisor>
</div>


@endsection
