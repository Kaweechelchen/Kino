<?php

namespace App\Http\Controllers;

use App\Screening;

class MoviesController extends Controller
{
    public function index($cinema = null)
    {
        $screenings = Screening::upcoming()->orderBy('screening')->get();

        return view(
            'screenings',
            compact(
                'screenings'
            )
        );
    }
}
