<?php

namespace App\Http\Controllers;

use App\Movie;
use App\MovieTitle;
use App\Screening;
use Illuminate\Support\Facades\Redis;

class CacheController extends Controller
{
    public function refresh()
    {
        $moviesRaw = Movie::get();
        $movies    = [];
        foreach ($moviesRaw as $movieRaw) {
            $movie  = (array) $movieRaw['original'];
            $titles = [];
            foreach ($movieRaw->titles as $titleRaw) {
                $titles[$titleRaw->language_id] = MovieTitle::where('movie_id', $movieRaw->id)->where('language_id', $titleRaw->language_id)->value('title');
            }
            $movie['titles']       = $titles;
            $movies[$movieRaw->id] = $movie;
        }

        Redis::set('morroni:movies:movies', json_encode($movies));
        Redis::expire('morroni:movies:movies', env('CACHE_TTL'));

        $screeningsRaw = Screening::orderBy('screening')->get();
        $screenings    = [];
        foreach ($screeningsRaw as &$screeningRaw) {
            $screening = [];
            foreach ($screeningRaw['movies'] as $key => $movie) {
                $screening[$movie->id][$movie->pivot->language_id][$movie->pivot->theatre_id] = [
                    'hall'   => $movie->pivot->hall,
                    'format' => $movie->pivot->format_id,
                ];
            }
            $screenings[$screeningRaw['screening']] = $screening;
        }

        Redis::set('morroni:movies:screenings', json_encode($screenings));
        Redis::expire('morroni:movies:screenings', env('CACHE_TTL'));
    }
}
