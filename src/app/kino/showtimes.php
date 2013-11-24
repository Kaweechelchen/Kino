<?php

    namespace kino;

	class Showtimes {

        static public function getShowtimes( $app ) {

            $showtimes_query = 'SELECT
                *
                FROM showtimes
                WHERE datetime >= CURRENT_TIMESTAMP()
                AND datetime < CURDATE() + INTERVAL 7 DAY
                ORDER BY datetime';

            $showtimes = $app['db']->fetchAll(

                $showtimes_query

            );

            return $showtimes;

        }

    }