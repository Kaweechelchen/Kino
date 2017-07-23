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
use App\GenreMovie;
use App\CategoryMovie;
use App\Screening;
use App\MovieScreening;

class ScrapeController extends Controller
{
    public function execute()
    {
        app('Kinepolis')->source(env('KINEPOLIS_BASE_URL'));
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
                GenreMovie::updateOrCreate(
                    [
                        'movie_id' => $movieId,
                        'genre_id' => $genre_id,
                    ]
                );
            }

            foreach ($movie['categories'] as $category_id) {
                CategoryMovie::updateOrCreate(
                    [
                        'movie_id'    => $movieId,
                        'category_id' => $category_id,
                    ]
                );
            }
        }

        foreach (app('Kinepolis')->screenings() as $screening) {
            $screening_id = Screening::updateOrCreate(
                [
                    'screening' => $screening['time'],
                ]
            )->id;

            MovieScreening::updateOrCreate(
                [
                    'movie_id'     => $screening['movie'],
                    'theatre_id'   => $screening['theatre'],
                    'hall'         => $screening['hall'],
                    'screening_id' => $screening_id,
                ],
                [
                    'format_id'   => $screening['format'],
                    'language_id' => $screening['language'],
                ]
            );
        }
    }
}
