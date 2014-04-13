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
                    'ageRestriction'    =>  $movie[ 'ageRestriction' ],
                    'posterURL'         =>  $movie[ 'posterURL'      ]
                )
            );

        }

        static public function screeningExists( $app, $screening ) {

            $screeningExists_query  = 'SELECT fiCinema, fiMovie, datetime
                FROM screenings
                WHERE fiCinema = ?
                AND   fiMovie  = ?
                AND   datetime = ?';

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

            $app['db']->insert(
                'screenings',
                array(
                    'fiCinema'  =>  $screening[ 'cinemaId' ],
                    'fiMovie'   =>  $screening[ 'movieId'  ],
                    'datetime'  =>  $screening[ 'datetime' ]
                )
            );

        }

    }