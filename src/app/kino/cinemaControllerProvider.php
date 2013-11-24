<?php

    namespace kino;

    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use kino\movies;
    use kino\screenings;


    class cinemaControllerProvider implements ControllerProviderInterface {

        public function connect( Application $app ) {

            $ctr = $app['controllers_factory'];

            $ctr->get( '/', function( Application $app ) {

                $screenings = Screenings::getScreenings( $app );

                $movies = Movies::getMovies( $app );

                return $app['twig']->render( 'index.twig',
                    array(
                        'movies'        =>  $movies,
                        'screenings'    =>  $screenings
                    )
                );


            });

            return $ctr;

        }

    }
