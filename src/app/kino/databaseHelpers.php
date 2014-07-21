<?php

    namespace kino;

    use Silex\Application;

    class DatabaseHelpers {

        static public function movieIdExists( $app, $movieId ) {

            $movieIdExists_query  = 'SELECT idRTL
                FROM movies
                WHERE idRTL = ?';

            $movieIdExists = $app['db']->fetchColumn(
                $movieIdExists_query,
                array(
                    (int) $movieId
                )
            );

            return $movieIdExists;

        }

        static public function saveMovie ( $app, $movie ) {

            $app['db']->insert(
                'movies',
                array(
                    'idRTL'             =>  $movie[ 'id'             ],
                    'title'             =>  $movie[ 'title'          ],
                    'duration'          =>  $movie[ 'duration'       ],
                    'language'          =>  $movie[ 'language'       ],
                    'genre'             =>  $movie[ 'genre'          ],
                    'synopsis'          =>  $movie[ 'synopsis'       ],
                    '3d'                =>  $movie[ '3d'             ],
                    'ageRestriction'    =>  $movie[ 'ageRestriction' ]
                )
            );

        }

        static public function screeningExists( $app, $screening ) {

            $screeningExists_query  = 'SELECT fiCinema, fiMovie, showtime
                FROM screenings, showtimes
                WHERE fiCinema = ?
                AND   fiMovie  = ?
                AND   showtime = ?
                AND   fiShowtime = idShowtime';

            $screeningExists = $app['db']->fetchColumn(
                $screeningExists_query,
                array(
                    (int) $screening[ 'cinemaId' ],
                    (int) $screening[ 'movieId'  ],
                          $screening[ 'datetime' ]
                )
            );

            return $screeningExists;

        }

        static public function saveScreening ( $app, $screening ) {

            if ( !empty( $screening[ 'datetime' ] ) && $screening[ 'datetime' ] != "0000-00-00 00:00:00" ) {

                $app['db']->insert(
                    'screenings',
                    array(
                        'fiCinema'      =>  $screening[ 'cinemaId' ],
                        'fiMovie'       =>  $screening[ 'movieId'  ],
                        'fiShowtime'    =>  self::saveShowtime ( $app, $screening[ 'datetime' ] )
                    )
                );

            }

        }

        static public function saveShowtime ( $app, $showtime ) {

            if ( !self::showtimeExists( $app, $showtime ) ) {

                $app['db']->insert(
                        'showtimes',
                        array(
                            'showtime'  =>  $showtime
                        )
                    );

                return $app['db']->lastInsertId();

            } else {

                return self::showtimeExists( $app, $showtime );

            }

        }

        static public function showtimeExists ( $app, $showtime ) {

            $showtimeExists_query  = 'SELECT idshowtime
                FROM showtimes
                WHERE showtime = ?';

            $showtimeExists = $app['db']->fetchColumn(
                $showtimeExists_query,
                array(
                    $showtime
                )
            );

            return $showtimeExists;

        }

        static public function saveCinemas ( $app, $cinemas ) {

            foreach ($cinemas as $cinemaId => $cinemaName) {

                if ( !self::cinemaExists( $app, $cinemaId ) ) {

                    $app['db']->insert(
                        'cinemas',
                        array(
                            'idCinema'  =>  $cinemaId,
                            'name'      =>  $cinemaName
                        )
                    );

                }

            }

        }

        static public function cinemaExists ( $app, $cinemaId ) {

            $cinemaExists_query  = 'SELECT idCinema
                FROM cinemas
                WHERE idCinema = ?';

            $cinemaExists = $app['db']->fetchColumn(
                $cinemaExists_query,
                array(
                    (int) $cinemaId
                )
            );

            return $cinemaExists;

        }

    }