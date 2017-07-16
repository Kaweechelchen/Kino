<?php

namespace App\Http\Controllers;

use App\Theatre;
use App\Language;
use App\Format;
use App\Country;
use Illuminate\Support\Facades\Redis;
use JavaScript;

class MoviesController extends Controller
{
    public function index()
    {
        $theatres   = Theatre::pluck('theatre', 'id');
        $languages  = Language::pluck('language', 'id');
        $formats    = Format::pluck('format', 'id');
        $countries  = Country::pluck('country', 'id');
        $movies     = json_decode(Redis::get('morroni:movies:movies'), true);
        $screenings = json_decode(Redis::get('morroni:movies:screenings'), true);

        $movies     = $movies ? $movies : [];
        $screenings = $screenings ? $screenings : [];

        JavaScript::put(
            compact(
                'theatres',
                'languages',
                'formats',
                'countries',
                'movies',
                'screenings'
            )
        );

        return view(
            'screenings'
        );
    }
}
