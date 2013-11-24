<?php

    namespace kino;

    use Silex\Application;
    use Silex\ControllerProviderInterface;

    class scrapeControllerProvider implements ControllerProviderInterface {

        static public function xmlByUrlToArray ( $url ) {

            $fileContents= file_get_contents( $url );

            $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);

            $fileContents = trim(str_replace('"', "'", $fileContents));

            $simpleXml = simplexml_load_string($fileContents);

            $json   = json_encode($simpleXml);

            return json_decode($json, true);

        }

        public function connect(Application $app) {

            $ctr = $app['controllers_factory'];

            $ctr->get( '/', function( Application $app ) {

                //  Parse the movieInfo XML data
                $movieInfo  =   self::xmlByUrlToArray( $app['MovieInfoURL'] );

                // All the data is in $movieInfo['movie']
                $movies     =   $movieInfo['movie'];

                //  Loop through each movie and save its data to the database
                foreach ($movies as $key => $movie) {

                    // make sure the array is empty
                    unset( $movieData );

                    // This is easy, these entries exist for every movie and do not need to be checked for existance
                    $movieData = array (
                        'api'       =>  'utopia',
                        'idmovie'   =>  $movie['id'],
                        'language'  =>  $movie['version'],
                    );

                    $notInTitle = array(
                        ' (VF)',
                        ' (VA)',
                        ' (VO)',
                        ' 3D',
                        ' 2D',
                        ' (VOst)',
                        ' (deutsche Fassung)',
                        ' VA',
                        ' VO'
                    );

                    $movieData['title'] = str_replace( $notInTitle, "", $movie['title'] );

                    //  For the following entries, we are going to check for the data that we get back,
                    //  they decided to send back an empty array in case there is no data...
                    $checkIfArray = array (
                        'genre',
                        'director',
                        'actor',
                        'synopsis',
                        'country',
                        'runtime'
                    );

                    // Check all the previous entries for existance...
                    foreach ( $checkIfArray as $value ) {
                        if ( !is_array( $movie[ $value ] ) ) {
                            $movieData[ $value ]  =   $movie[ $value ];
                        }
                    }

                    //  Next up we have to check for age restrictions and possible 3D screenings
                    if ( !empty( $movie['icons'] ) ) {

                        // This data is in $movie['icons']['feature']
                        $feature = $movie['icons']['feature'];

                        //  If they only have one feature, if will probably be the age restriction
                        if ( array_key_exists( 'description', $feature ) ) {

                            if ( $feature['description'] != '3D' ) {

                                $age = $feature['description'];

                            }

                        } else {

                            //  If there is information about a 3D screening they are going to send back multiple arrays here.
                            //  The age restriction is always going to be in the last one
                            foreach ($feature as $value) {

                                // Just checking if this array is about 3D screeing.
                                if ( $value['description'] == '3D' ) {

                                    $movieData['extra3D'] = true;

                                } else {

                                    // In case it is not, we are going to set this value on every loop.
                                    // As I said before, the last one is about the age restriction
                                    $age = $value['description'];

                                }
                            }

                        }

                        // Store the age restriction in the array
                        $movieData['age']  =   $age;

                    }

                    // Check if a movie with the movieId has already been inserted into the database

                    $MovieExists = $app['db']->fetchColumn(
                        'SELECT COUNT(*) FROM movies WHERE idmovie = ?',
                        array( $movieData['idmovie'] ),
                        0
                    );

                    //Check if there is already an entry for the movieId we are trying to save to the database

                    if ( !$MovieExists ) {

                        $app['db']->insert(
                            'movies',
                            $movieData
                        );

                    } else {

                        //If there is already an entry for the movieId => update the entry

                        $app['db']->update(
                            'movies',
                            $movieData,
                            array('idmovie' => $movieData['idmovie'])
                        );

                    }
                }

                //  Parse the showtimes XML data
                $showTimes  =   self::xmlByUrlToArray( $app['ShowtimesURL'] );

                //  All the data is in $movieInfo['movie']
                $schedules     =   $showTimes['schedule'];

                foreach ( $schedules as $cinema ) {

                    $cinemaID = $cinema[ 'cinemaid' ];
                    $screenings = $cinema[ 'screenings' ][ 'screening' ];

                    foreach ( $screenings as $screening ) {

                        $movieId = $screening[ 'movieid' ];

                        $dates = $screening[ 'dates' ][ 'date' ];
                        if ( !is_array( $dates ) ) {
                            $dates = array( $dates );
                        }

                        foreach ( $dates as $date ) {

                            $showtimes[ $date ][ $movieId ][] = $cinemaID;

                        }

                    }

                }

                foreach ($showtimes as $showtime => $movies) {

                    $showtimeId = $app['db']->fetchColumn(
                        'SELECT idshowtime FROM showtimes WHERE datetime = ?',
                        array( $showtime ),
                        0
                    );

                    if ( !$showtimeId ) {

                        // Store the showtimes in the Database
                        $app['db']->insert(

                            'showtimes',
                            array(
                                'datetime' => $showtime
                            )

                        );

                        $showtimeId = $app['db']->lastInsertId();

                    }



                    foreach ($movies as $movie => $cinemas) {

                        foreach ($cinemas as $cinema) {

                            $screeningExists = $app['db']->fetchColumn(
                                'SELECT COUNT(*)
                                    FROM screenings
                                    WHERE fimovie = ?
                                    AND fishowtime = ?
                                    AND ficinema   = ?',
                                array(
                                    $movie,
                                    $showtimeId,
                                    $cinema
                                ),
                                0
                            );

                            if ( !$screeningExists ) {

                                $app['db']->insert(

                                    'screenings',
                                    array(
                                        'fimovie'       =>  $movie,
                                        'fishowtime'    =>  $showtimeId,
                                        'ficinema'      =>  $cinema
                                    )

                                );

                            }

                        }

                    }

                }

                return true;

            });

            return $ctr;

        }

    }
