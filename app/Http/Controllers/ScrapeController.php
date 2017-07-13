<?php

namespace App\Http\Controllers;

class ScrapeController extends Controller
{
    public function execute()
    {
        app('Kinepolis')->source('rawData');
        app('Kinepolis')->load();
        app('Kinepolis')->scrape();
        dd(app('Kinepolis')->data());
        dd('scraping...');
    }
}
