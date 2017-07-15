<?php

namespace App\Http\Controllers;

use App\Theatre;
use App\Language;
use App\Format;
use App\Movie;
use App\Country;
use Illuminate\Support\Facades\Redis;

class MoviesController extends Controller
{
    public function index()
    {
        $theatre    = Theatre::pluck('theatre', 'id');
        $language   = Language::pluck('language', 'id');
        $format     = Format::pluck('format', 'id');
        $country    = Country::pluck('country', 'id');
        $movie      = json_decode(Redis::get('morroni:movies:movies'), true);
        $screenings = json_decode(Redis::get('morroni:movies:screenings'), true);

        return compact(
            'theatre',
            'language',
            'format',
            'country',
            'movie',
            'screenings'
        );

        return json_decode(Redis::get('screenings'), true);

        return compact(
            //'screenings',
            'theatres',
            'languages',
            'formats',
            'movieTitles'
        );

        return view(
            'screenings',
            compact(
                'screenings',
                'theatres',
                'languages',
                'formats',
                'movieTitles'
            )
        );
    }
}
