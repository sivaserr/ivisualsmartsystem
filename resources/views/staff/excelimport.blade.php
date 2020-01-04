@extends('layout.app')


@section('content')
<div id="app">
{{-- {!! Form::open(['action' => 'StaffController@import', 'method' => 'post' ,'enctype' => 'multipart/data']) !!}
<div class="input-group mb-3">
{!! Form::file('file'); !!}
</div>
{!! Form::submit('Click Me!') !!}
{!! Form::close() !!} --}}

<form action="{{ route('import-staffs') }}" method="POST" enctype="multipart/form-data">
    
    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
    <input type="file" name="file" id="file" >
    <button type="submit">Submit</button>
</form>


</div>


@endsection

