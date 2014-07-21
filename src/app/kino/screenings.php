<?php

    namespace kino;

    use Silex\Application;

    class Screenings {

        static public function getScreeningsByShowtime ( Application $app ) {

            foreach ( self::getUpcomingShowtimes( $app )  as $showtimeId => $showtime) {

                $moviesByShowtime = array();

                $moviesByShowtime_query = 'SELECT
                    *
                    FROM screenings
                    WHERE fiShowtime = ?';

                $moviesByShowtime = $app['db']->fetchAll(
                    $moviesByShowtime_query,
                    array(
                        $showtimeId
                    )
                );

                foreach ( $moviesByShowtime as $movie ) {

                    $movies[ $movie[ 'fiMovie' ] ][] = $movie[ 'fiCinema' ];

                }

                $getScreeningsByShowtime[ $showtime['showtime'] ] = $movies;

            }

            return $getScreeningsByShowtime;

        }

        static public function getShowtimes ( Application $app ) {

            $showtimes_query = 'SELECT
                *
                FROM showtimes
                ORDER BY showtime';

            $showtimes = $app['db']->fetchAll(
                $showtimes_query
            );

            foreach( $showtimes as $showtime ){
                $idxShowtimes[$showtime['idShowtime']] = $showtime;
            }

            return $idxShowtimes;

        }

        static public function getUpcomingShowtimes ( Application $app ) {

            $showtimes_query = 'SELECT
                *
                FROM showtimes
                WHERE showtime >= NOW()
                ORDER BY showtime
                LIMIT 10';

            $showtimes = $app['db']->fetchAll(
                $showtimes_query
            );

            foreach( $showtimes as $showtime ){
                $idxShowtimes[$showtime['idShowtime']] = $showtime;
            }

            return $idxShowtimes;

        }

        static public function getScreeningsById ( Application $app, $screeningId ) {

            $screening_query = 'SELECT
                *
                FROM screenings
                WHERE idScreening = ?
                ORDER BY datetime';

            $screening = $app['db']->fetchAll(
                $screening_query,
                array(
                    $screeningId
                )
            );

            return $screening;

        }

        static public function getScreeningsAfterTimestamp ( Application $app, $timestamp ) {



            $screenings_query = 'SELECT
                *
                FROM screenings
                WHERE datetime > FROM_UNIXTIME( ? )
                ORDER BY datetime';

            $screenings = $app['db']->fetchAll(
                $screenings_query,
                array(
                    $timestamp
                )
            );

            return $screenings;

        }

    }