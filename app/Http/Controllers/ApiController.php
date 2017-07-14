<?php

namespace App\Http\Controllers;

use App\Screening;

class ApiController extends Controller
{
    public function screening(Screening $screening)
    {
        return $screening->movie;
    }
}
