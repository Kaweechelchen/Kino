@extends('layout')
@section('content')
    <div class="col-md-9">
        @foreach ($screenings as $key => $screening)
            <div class="well">
                @foreach ($screening->movies as $key => $movie)
                    <div class="well">
                        <ul>

                            <li>
                                {{ $movie }}
                            </li>

                            <li>
                                {{ $movieTitles->where('movie_id', $movie->id)->where('language_id', $movie->pivot->language_id)->first()['title'] }}
                            </li>

                            <li>
                                {{ $formats->find($movie->pivot->format_id)->format }}
                            </li>

                            <li>
                                {{ $languages->find($movie->pivot->language_id)->language }}
                            </li>

                            <li>
                                {{ $theatres->find($movie->pivot->theatre_id)->theatre }}
                            </li>
                        </ul>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@stop
