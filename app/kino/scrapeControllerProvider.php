<?php

    // Thierry Degeling [@Kaweechelchen]

    namespace kino;

    use Silex\Application;
    use Silex\ControllerProviderInterface;

    class scrapeControllerProvider implements ControllerProviderInterface {

        static public function getXMLData( $url ) {

            $xml  = file_get_contents( $url );
            $data = simplexml_load_string( $xml );
            $json = json_encode( $data );
            return  json_decode( $json, true );

        }

        public function connect( Application $app ) {

            $ctr = $app['controllers_factory'];

            /**
             * Return some JSON in case a bus station as been passed as a parameter
             */
            $ctr->get( '/', function( Application $app ) {

                $result = array();

                // Contains the URLs to get the information from Utopolis
                $API_URLs = array(

                    'showtimes'  => 'http://www.sharewire.net/mc2/v2.0/api/index.php?mc=54&v=0.1&item=showtimes&params=country=lu--language=en',
                    'movieInfo'  => 'http://www.sharewire.net/mc2/v2.0/api/index.php?mc=54&v=0.1&item=movieinfo&params=country=lu--language=en',
                    'cinemaInfo' => 'http://www.sharewire.net/mc2/v2.0/api/index.php?mc=54&v=0.1&item=cinemainfo&params=country=lu--language=en'

                );

                $cinemaInfo_lgc = self::getXMLData( $API_URLs[ 'cinemaInfo' ] )[ 'cinema' ];

                foreach ( $cinemaInfo_lgc as $cinema ) {

                    $result[ 'cinemas' ][ $cinema[ 'id' ] ] = $cinema[ 'name' ];

                }

                $showtimes_lgc = self::getXMLData( $API_URLs[ 'showtimes' ] )[ 'schedule' ];

                $screenings = array();

                foreach ( $showtimes_lgc as $cinema ) {

                    $cinemaId = $cinema[ 'cinemaid' ];

                    foreach ( $cinema[ 'screenings' ][ 'screening' ] as $screening ) {

                        $movieid = $screening[ 'movieid' ];

                        if ( sizeof( $screening[ 'dates' ][ 'date' ] ) == 1 ) {

                            $screenings[ strtotime(
                                $screening[ 'dates' ][ 'date' ].':00'
                            ) ][ $movieid ][] = $cinemaId;

                        } else {

                            foreach ( $screening[ 'dates' ][ 'date' ] as $showtime ) {

                                $screenings[ strtotime(
                                    $showtime.':00'
                                ) ][ $movieid ][] = $cinemaId;

                            }

                        }

                    }

                }

                $result[ 'screenings' ] = $screenings;

                $movies_lgc = self::getXMLData( $API_URLs[ 'movieInfo' ] )[ 'movie' ];

                foreach ( $movies_lgc as $movie_lgs ) {

                    $movie[ 'title' ] = $movie_lgs[ 'title' ];
                    $movie[ 'synopsis' ] = $movie_lgs[ 'synopsis' ];
                    $movie[ 'runtime' ] = $movie_lgs[ 'runtime' ];
                    $movie[ 'language' ] = $movie_lgs[ 'version' ];

                    if ( in_array( 'poster', $movie_lgs[ 'media' ][ 'image' ] ) ) {

                        $movie[ 'poster' ] = $movie_lgs[ 'media' ][ 'image' ][ 'url' ];

                    } else {

                        foreach ( $movie_lgs[ 'media' ][ 'image' ] as $image ) {

                            if ( in_array( 'poster', $image ) ) {

                                $movie[ 'poster' ] = $image[ 'url' ];

                            }

                        }

                    }

                    file_put_contents(
                        "scrape/img/" . $movie_lgs[ 'id' ] . ".jpg",
                        fopen(
                            $movie[ 'poster' ],
                            'r'
                        )
                    );

                    $movies[ $movie_lgs[ 'id' ] ] = $movie;

                }

                $result[ 'movies' ] = $movies;

                file_put_contents(
                    "api.json",
                    json_encode( $result )
                );

                return 'scrape complete';

            });

            // return the controller
            return $ctr;

        }

    }
