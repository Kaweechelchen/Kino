<?php

    namespace kino;

    use Silex\Application;

	class RTL {

        static public function getShowTimesByCinemaAndDate ( $app, $cinemaId, $date )  {

            $moviePatterns = $app['moviePatterns'];

            $url = 'http://kultur.rtl.lu/kino/deprogramm/salle?salle%d_seance%d.html';

            $url = urlencode( sprintf( $url, $cinemaId, $date ) );

            $proxy = 'http://getcontents.herokuapp.com/?url=';

            $screeningData = file_get_contents( $proxy . $url );

            $moviesPattern = '/\<div class="movie">(.*?)<div class="divider">/s';

            preg_match_all( $moviesPattern, $screeningData, $moviesMatches );

            $movies = $moviesMatches[1];

            $screeningTableRowPattern = '/<tr>(.*?)<\/tr>/';

            $screeningPattern = '/<td(.*?)<\/td>/';

            foreach ($movies as $key => $movieData) {

                foreach ( $moviePatterns as $key => $value) {

                    preg_match(
                        $moviePatterns[ $key ],
                        $movieData,
                        $movieMatch
                    );

                    $movie[$key] = $movieMatch[1];

                }

                $movieId = Helpers::saveMovie ( $app, $movie );

                preg_match_all($screeningTableRowPattern, $movieData, $screeningRows);

                $screeningRows = $screeningRows[1];

                unset( $screeningRows[0] );

                foreach ($screeningRows as $key => $screeningRow) {

                    preg_match_all($screeningPattern, $screeningRow, $screenings);

                    $screenings = $screenings[0];

                    $weekDayCount = 0;

                    foreach ($screenings as $key => $screeningTime) {

                        $screeningTime = strip_tags($screeningTime);

                        if ( !empty( $screeningTime ) ) {

                            $screeningDate = date( 'Y-m-d', strtotime( $date + $weekDayCount ) );

                            $datetime = $screeningDate . ' ' .$screeningTime;

                            helpers::saveScreening( $app, $movieId, $cinemaId, $datetime );

                        }

                        $weekDayCount++;

                    }

                }

            }

        }

    }
