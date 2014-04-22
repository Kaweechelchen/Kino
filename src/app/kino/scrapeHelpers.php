<?php

    namespace kino;

    use Silex\Application;

    class ScrapeHelpers {


        /**
         * Get Content from a URL via a proxy
         * @param  [string] $url [the URL]
         * @return [string]      [contents from the URL]
         */
        static public function getContent ( $url ) {

            var_dump( $url ); exit;

            $proxy = 'http://getcontents.herokuapp.com/?url=';

            return file_get_contents( $proxy . $url );

        }

        /**
         * Returns the actors as array for the given movie data
         * @param  [string] $movieData [Movie data from defined source]
         * @return [array]             [actors as different items]
         */
        static public function getActors ( $movieData ) {

            preg_match(
                '/>Acteurs<\/th><td>(.*?)<\/td>/',
                $movieData,
                $actors
            );

            if ( array_key_exists( 1, $actors ) ) {

                return explode(", ", $actors[1]);

            } else {

                return array();

            }

        }

        /**
         * Returns the duration for the given movie data
         * @param  [string] $movieData [Movie data from defined source]
         * @return [string]            [duration in minutes]
         */
        static public function getDuration ( $movieData ) {

            preg_match(
                '/>Durée<\/th><td>(.*?)<\/td>/',
                $movieData,
                $duration
            );

            return $duration[1];

        }

        /**
         * Returns the age restriction for the given movie data
         * @param  [string] $movieData [Movie data from defined source]
         * @return [integer]           [age in years]
         */
        static public function getAgeRestriction ( $movieData ) {

            preg_match(
                '/>Admission<\/th><td>(.*?)<\/td>/',
                $movieData,
                $restriction
            );

            if ( $restriction[1] == 'Enfants Admis' ) {

                return 0;

            } else {

                return substr(
                    $restriction[1],
                    0,
                    strpos(
                        $restriction[1],
                        ' ans'
                        )
                    );

            }

        }

        /**
         * Returns the synopsis for the given movie data
         * @param  [string] $movieData [Movie data from defined source]
         * @return [string]            [synopsis of the movie]
         */
        static public function getSynopsis ( $movieData ) {

            preg_match(
                '/<p>(.*?)<\/p>/',
                $movieData,
                $synopsis
            );

            return $synopsis[1];

        }

        /**
         * Returns the genre for the given movie data
         * @param  [string] $movieData [Movie data from defined source]
         * @return [string]            [the genre of the movie]
         */
        static public function getGenre ( $movieData ) {

            preg_match(
                '/>Genre<\/th><td>(.*?)<\/td>/',
                $movieData,
                $genre
            );

            return $genre[1];

        }

        /**
         * Returns the id for the given movie data
         * @param  [string]  $movieData [Movie data from defined source]
         * @return [integer]            [the id of the movie]
         */
        static public function getMovieId ( $movieData ) {

            preg_match(
                '/kultur\/film-a-kino\/programm\/film\?id=(.*?)&seance/',
                $movieData,
                $movieId
            );

            return $movieId[1];

        }

        /**
         * Returns the name for the given movie data
         * @param  [string] $movieData [Movie data from defined source]
         * @return [type]              [the title of the movie]
         */
        static public function getMovieTitle ( $movieData ) {

            preg_match(
                '/\" class="title">(.*?)<\/a>/',
                $movieData,
                $movieTitle
            );

            $movieTitle = $movieTitle[1];

            if ( strpos( $movieTitle, ' - 3D' ) !== FALSE ) {

                    $movieTitle = substr(
                        $movieTitle,
                        0,
                        strpos( $movieTitle, ' - 3D' )
                    );

                }

            return html_entity_decode( $movieTitle );

        }

        /**
         * Extract the table containing the cinemas from Luxembourg from a webpage
         * @param  [string] $website [The webpage containg information about the cinemas]
         * @return [string]          [The html table containing the cinemas]
         */
        static public function getCinemaTable ( $website ) {

            preg_match(
                '/\<table width="100(.*?)<\/table/s',
                $website,
                $cinemaMatches
            );

            return $cinemaMatches[1];

        }

        /**
         * Return the cinema names as array for a given table holding names
         * @param  [string] $cinemaTable [Table containing names]
         * @return [array]               [Array containing the names of the cinemas]
         */
        static public function getCinemas ( $cinemaTable ) {

            preg_match_all(
                '/\<td width="38%">(.*?) <span/',
                $cinemaTable,
                $cinemaNameMatches
            );

            $cinemaNames = $cinemaNameMatches[1];

            preg_match_all(
                '/\?salle=(.*)&seance=today">/',
                $cinemaTable,
                $cinemaIdMatches
            );

            $cinemaIds = $cinemaIdMatches[1];

            foreach( $cinemaIds as $arrayKey => $cinemaID ) {
                $cinemas[ $cinemaID ] = $cinemaNames[ $arrayKey ];
            }

            return $cinemas;

        }

        /**
         * Return the poster image URI for the given movie data
         * @param  [string ] $movieData [Movie data from defined source]
         * @return [boolean]            [Bool if it worked]
         */
        static public function getPoster ( $app, $movieData, $movieId ) {

            preg_match(
                '/<img src="http:\/\/images.newmedia.lu\/rtl.lu\/kino\/affiches\/(.*?)">/',
                $movieData,
                $posterURL
            );

            $url = substr( $posterURL[0], 10, -2 );
            $img = __DIR__ . "/../../web/img/posters/$movieId.jpg";

            if ( !$app['offline'] ) {

                file_put_contents($img, self::getContent($url));

            }

            return true;

        }

    }