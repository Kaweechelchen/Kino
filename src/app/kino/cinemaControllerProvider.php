<?php

    namespace kino;

    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use kino\movies;
    use kino\screenings;
    use kino\actors;


    class cinemaControllerProvider implements ControllerProviderInterface {

        public function connect( Application $app ) {

            $ctr = $app['controllers_factory'];

            $ctr->get( '/', function( Application $app ) {

                return $app['twig']->render( 'new.twig',
                    array(
                        'movies'                =>  Movies::getMovies( $app ),
                        'screeningsByShowtime'  =>  Screenings::getScreeningsByShowtime( $app )
                    )
                );


            });

            return $ctr;

        }

    }
