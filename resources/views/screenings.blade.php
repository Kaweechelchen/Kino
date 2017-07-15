@extends('layout')
@section('content')
    <div class="col-md-9">
    @foreach ($screenings as $screening => $movies)
        {{ date('H:i', strtotime($screening)) }}
        <div class="well">
        @foreach($movies as $movieId => $languageScreenings)
            <div class="well">
            @foreach ($languageScreenings as $languageId => $theatres)

                <b>Title: {{$movie[$movieId]['titles'][preg_replace('/_(.*)/', '', $languageId)] }}</b>
                <i>{{ $language[$languageId] }}</i>
                <ul>
                @foreach ($theatres as $theatreId => $theatreMeta)
                    <li>
                        Theatre: {{ $theatre[$theatreId] }} >> {{ $theatreMeta['hall'] }} >> {{ $format[$theatreMeta['format']] }}
                    </li>
                @endforeach
                </ul>
            @endforeach
            </div>
        @endforeach
        </div>
    @endforeach
    </div>
@stop
