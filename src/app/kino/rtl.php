<?php

    namespace kino;

    use Silex\Application;

	class rtl {

        static public function getShowTimesByCinemaAndDate ( $app, $cinemaId, $date )  {

            $moviePatterns = $app['moviePatterns'];

            $url = 'http://kultur.rtl.lu/kino/deprogramm/salle?salle%d_seance%d.html';

            $url = urlencode( sprintf( $url, $cinemaId, $date ) );

            $proxy = 'http://getcontents.herokuapp.com/?url=';

            //$screeningData = file_get_contents( $proxy . $url );
            //
            $screeningData = file_get_contents( 'salle2_seance20131127.html' );

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

                rottentomatoes::getMovieData ( $movie['name'] );

                echo $movie['id'] .' >> ' . $movie['name'] . ' >> ' . $movie['language'] . '<br />';

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

                            $screeningDate = date( 'd.m.Y', strtotime( $date + $weekDayCount ) );

                            echo "$screeningDate >> $screeningTime $weekDayCount<br />";

                        }

                        $weekDayCount++;

                    }

                }

            }

        }

    }
