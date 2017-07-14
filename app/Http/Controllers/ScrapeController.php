<?php

namespace App\Http\Controllers;

use App\Genre;
use App\Language;
use App\Category;
use App\Format;
use App\Type;
use App\Country;
use App\Theatre;
use App\Movie;
use App\Movietitle;
use App\Moviedirector;
use App\Movieactor;
use App\Moviegenre;
use App\Moviecategory;

class ScrapeController extends Controller
{
    public function execute()
    {
        app('Kinepolis')->source('rawData');
        app('Kinepolis')->load();
        app('Kinepolis')->scrape();

        foreach (app('Kinepolis')->genres() as $genreId => $genre) {
            Genre::updateOrCreate(['id' => $genreId], ['genre' => $genre]);
        }

        foreach (app('Kinepolis')->languages() as $languageId => $language) {
            Language::updateOrCreate(['id' => $languageId], ['language' => $language]);
        }

        foreach (app('Kinepolis')->categories() as $categoryId => $category) {
            Category::updateOrCreate(['id' => $categoryId], ['category' => $category]);
        }

        foreach (app('Kinepolis')->formats() as $formatId => $format) {
            Format::updateOrCreate(['id' => $formatId], ['format' => $format]);
        }

        foreach (app('Kinepolis')->types() as $typeId => $type) {
            Type::updateOrCreate(['id' => $typeId], ['type' => $type]);
        }

        foreach (app('Kinepolis')->countries() as $countryId => $country) {
            Country::updateOrCreate(['id' => $countryId], ['country' => $country]);
        }

        foreach (app('Kinepolis')->theatres() as $theatreId => $theatre) {
            Theatre::updateOrCreate(['id' => $theatreId], ['theatre' => $theatre]);
        }

        foreach (app('Kinepolis')->movies() as $movieId => $movie) {
            Movie::updateOrCreate(
                ['id' => $movieId],
                [
                    'synopsis'   => $movie['synopsis'],
                    'length'     => $movie['length'],
                    'imdb'       => $movie['imdb'],
                    'country_id' => $movie['country'],
                ]
            );

            foreach ($movie['titles'] as $language_id => $title) {
                Movietitle::updateOrCreate(
                    [
                        'movie_id'    => $movieId,
                        'language_id' => $language_id,
                    ],
                    ['title' => $title]
                );
            }

            foreach ($movie['directors'] as $director) {
                Moviedirector::updateOrCreate(
                    [
                        'movie_id' => $movieId,
                        'director' => $director,
                    ]
                );
            }

            foreach ($movie['cast'] as $actor) {
                Movieactor::updateOrCreate(
                    [
                        'movie_id' => $movieId,
                        'actor'    => $actor,
                    ]
                );
            }

            foreach ($movie['genres'] as $genre_id) {
                Moviegenre::updateOrCreate(
                    [
                        'movie_id' => $movieId,
                        'genre_id' => $genre_id,
                    ]
                );
            }

            foreach ($movie['categories'] as $category_id) {
                Moviecategory::updateOrCreate(
                    [
                        'movie_id'    => $movieId,
                        'category_id' => $category_id,
                    ]
                );
            }
        }

        dd(app('Kinepolis')->data());
    }
}
