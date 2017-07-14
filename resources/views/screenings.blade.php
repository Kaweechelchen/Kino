@extends('layout')
@section('content')
    <div class="col-md-9">
        @foreach ($screenings as $key => $screening)
            <div class="well">
                {{ $screening }}
            </div>
        @endforeach
    </div>
@stop
