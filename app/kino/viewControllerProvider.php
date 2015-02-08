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

        static public function getUpcomingScreenings( Application $app, $cinema = false ) {

          $json = json_decode(
            file_get_contents( 'api.json' ),
            true
          );

          foreach ( $json[ 'screenings' ] as $time => $jsonScreening ) {

            if ( ($time > time() - (10*60) ) && ($time < time() + (36*60*60) ) ) {

              $cinamas = array('kirchberg', 'belval', 'utopia');
              if ( in_array( $cinema, $cinamas ) ) {

                switch ( $cinema ) {
                  case 'kirchberg': $requestedCinemaId = 'lu1';  break;
                  case 'belval':    $requestedCinemaId = 'lu23'; break;
                  case 'utopia':    $requestedCinemaId = 'lu2';  break;
                }

                foreach ($jsonScreening as $movie => $cinemaId) {

                  if ( in_array( $requestedCinemaId, $cinemaId ) ) {
                    $screenings[ $time ][$movie] = $cinemaId;
                  }

                }

              } else {

                $screenings[ $time ] = $jsonScreening;

              }

            }

          }

          ksort( $screenings );
          return $screenings;

        }

        static public function getGridMovies( Application $app ) {

          $json = json_decode(
            file_get_contents( 'api.json' ),
            true
          );

          foreach ( $json[ 'screenings' ] as $time => $jsonScreening ) {

            if ( ($time > time() - (10*60) ) && ($time < time() + (36*60*60) ) ) {

              foreach ( $jsonScreening as $movieId => $cinemas ) {

                foreach ( $cinemas as $cinema ) {

                  $movies[ $movieId ][ $time ] = $cinema;

                }

              }

            }

          }

          foreach ( $movies as $movieId => $movieData ) {

            ksort( $movies[ $movieId ] );

          }

          return $movies;

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

            $ctr->get( '/{cinema}', function( Application $app, $cinema ) {

              if ( $cinema == 'grid' ) {

                return $app['twig']->render(
                  'grid.kino.mona.lu.twig',
                  array(
                    'screenings' => self::getGridMovies( $app ),
                    'cinemas'    => self::getCinemas   ( $app ),
                    'movies'     => self::getMovies    ( $app )
                  )
                );

              } else {

                return $app['twig']->render(
                  'kino.mona.lu.twig',
                  array(
                    'cinemas'    => self::getCinemas            ( $app ),
                    'screenings' => self::getUpcomingScreenings ( $app, $cinema ),
                    'movies'     => self::getMovies             ( $app )
                  )
                );

              }

            });

            // return the controller
            return $ctr;

        }

    }
