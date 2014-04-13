<?php

    namespace kino;

    use Silex\Application;

    class Screenings {

        static public function getScreenings( Application $app ) {

            $screenings_query = 'SELECT
                *
                FROM screenings
                ORDER BY datetime';

            $screenings = $app['db']->fetchAll(
                $screenings_query
            );

            foreach( $screenings as $screening ){
                $idxScreenings[$screening['idScreening']] = $screening;
            }

            return $idxScreenings;

        }

        static public function getUpcomingScreenings( Application $app ) {

            $screenings_query = 'SELECT
                *
                FROM screenings
                WHERE datetime >= NOW()
                ORDER BY datetime';

            $screenings = $app['db']->fetchAll(
                $screenings_query
            );

            foreach( $screenings as $screening ){
                $idxScreenings[$screening['idScreening']] = $screening;
            }

            return $idxScreenings;

        }

        static public function getScreeningsById( Application $app, $screeningId ) {

            $screening_query = 'SELECT
                *
                FROM screenings
                WHERE idScreening = ?';

            $screening = $app['db']->fetchAll(
                $screening_query,
                array(
                    $screeningId
                )
            );

            return $screening;

        }

    }