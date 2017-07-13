<?php

namespace App\Http\Controllers;

use App\Genre;
use App\Language;
use App\Category;
use App\Format;
use App\Type;
use App\Country;
use App\Theatre;

class ScrapeController extends Controller
{
    public function execute()
    {
        app('Kinepolis')->source('rawData');
        app('Kinepolis')->load();
        app('Kinepolis')->scrape();

        foreach (app('Kinepolis')->genres() as $genreId => $genre) {
            Genre::updateOrCreate(
                ['id' => $genreId],
                ['genre' => $genre]
            );
        }

        foreach (app('Kinepolis')->languages() as $languageId => $language) {
            Language::updateOrCreate(
                ['id' => $languageId],
                ['language' => $language]
            );
        }

        foreach (app('Kinepolis')->categories() as $categoryId => $category) {
            Category::updateOrCreate(
                ['id' => $categoryId],
                ['category' => $category]
            );
        }

        foreach (app('Kinepolis')->formats() as $formatId => $format) {
            Format::updateOrCreate(
                ['id' => $formatId],
                ['format' => $format]
            );
        }

        foreach (app('Kinepolis')->types() as $typeId => $type) {
            Type::updateOrCreate(
                ['id' => $typeId],
                ['type' => $type]
            );
        }

        foreach (app('Kinepolis')->countries() as $countryId => $country) {
            Country::updateOrCreate(
                ['id' => $countryId],
                ['country' => $country]
            );
        }

        foreach (app('Kinepolis')->theatres() as $theatreId => $theatre) {
            Theatre::updateOrCreate(
                ['id' => $theatreId],
                ['theatre' => $theatre]
            );
        }

        dd(app('Kinepolis')->genres());
        dd(app('Kinepolis')->data());
    }
}
