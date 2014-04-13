<?php

    namespace kino;

	class Cinemas {

        static public function getCinemas() {

            foreach (Data::getCinema() as $cinema) {

                $id = $cinema['id'];
                $cinemas[$id] = $cinema;

            }

            return $cinemas;

        }

        static public function getCinemaById( $id ) {

            $cinemas = self::getCinemas();
            return $cinemas[$id];

        }

        static public function getCinemaNameById( $id ) {

            $cinema = self::getCinemaById( $id );
        	return $cinema[ 'name' ];

        }

    }