<?php

    // Thierry Degeling [@Kaweechelchen]

    namespace kino;

    use Silex\Application;
    use Silex\ControllerProviderInterface;

    class viewControllerProvider implements ControllerProviderInterface {

        static public function getCinemas( Application $app ) {

            $json = json_decode(
                file_get_contents( 'api.json' ),
                true
            );

            return $json[ 'cinemas' ];

        }

        static public function getUpcomingScreenings( Application $app ) {

            $json = json_decode(
                file_get_contents( 'api.json' ),
                true
            );

            foreach ( $json[ 'screenings' ] as $time => $jsonScreening ) {

                if ( $time > time() ) {

                    $screenings[ $time ] = $jsonScreening;

                }

            }

            ksort( $screenings );

            return $screenings;

        }

        static public function getMovies( Application $app ) {

            $json = json_decode(
                file_get_contents( 'api.json' ),
                true
            );

            return $json[ 'movies' ];

        }

        public function connect( Application $app ) {

            $ctr = $app['controllers_factory'];

            $ctr->get( '/', function( Application $app ) {
                return $app['twig']->render(
                    'kino.mona.lu.twig',
                    array(
                        'cinemas'    => self::getCinemas            ( $app ),
                        'screenings' => self::getUpcomingScreenings ( $app ),
                        'movies'     => self::getMovies             ( $app )
                    )
                );
            });

            // return the controller
            return $ctr;

        }

    }
