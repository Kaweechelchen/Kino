<?php

namespace App\Http\Controllers;

class MoviesController extends Controller
{
    public function index($cinema = null)
    {
        return array('hello');
    }
}
