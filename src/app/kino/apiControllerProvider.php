<?php

    namespace kino;

    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use kino\movies;
    use kino\screenings;
    use kino\actors;


    class apiControllerProvider implements ControllerProviderInterface {

        public function connect( Application $app ) {

            $ctr = $app['controllers_factory'];

            $ctr->get( 'movies', function( Application $app ) {

                return $app->json(

                    Movies::getMovies( $app )

                );

            });

            $ctr->get( 'movie/{movieId}', function( Application $app, $movieId ) {

                return $app->json(

                    Movies::getMovieById( $app, $movieId )

                );

            });

            $ctr->get( 'screenings/', function( Application $app ) {

                return $app->json(

                    Screenings::getUpcomingScreenings( $app )

                );

            });

            $ctr->get( 'screenings/{timestamp}', function( Application $app, $timestamp ) {

                return $app->json(

                    Screenings::getScreeningsAfterTimestamp( $app, $timestamp )

                );

            });

            return $ctr;

        }

    }
