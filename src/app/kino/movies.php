<?php

    namespace kino;

    use Silex\Application;

    class Movies {

        static public function getMovies( Application $app ) {

            $movies_query = 'SELECT
                *
                FROM movies
                ORDER BY title';

            $movies = $app['db']->fetchAll(
                $movies_query
            );

            foreach( $movies as $movie ){
                $idxMovies[$movie['idRTL']] = $movie;
            }

            return $idxMovies;

        }

        static public function getMovieById( Application $app, $movieId ) {

            $movie_query = 'SELECT
                *
                FROM movies
                WHERE idRTL = ?';

            $movie = $app['db']->fetchAll(
                $movie_query,
                array(
                    $movieId
                )
            );

            return $movie;

        }

    }