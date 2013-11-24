<?php

    namespace kino;

    use kino\showtimes;

	class Screenings {

        static public function getScreenings( $app ) {

            $showtimes = Showtimes::getShowtimes( $app );

            foreach ($showtimes as $showtime) {

                $datetime = $showtime['datetime'];

                $screeningsByShowtime_query = 'SELECT
                    *
                    FROM screenings
                    WHERE fishowtime = ?';

                $screeningsByShowtime = $app['db']->fetchAll(

                    $screeningsByShowtime_query,
                    array(
                        $showtime['idshowtime']
                    )

                );

                foreach ($screeningsByShowtime as $screeningByShowtime) {

                    $idmovie = $screeningByShowtime['fimovie'];
                    $idcinema = $screeningByShowtime['ficinema'];

                    $screenings[$datetime][$idmovie][] = $idcinema;

                }


            }

            return $screenings;


        }

    }