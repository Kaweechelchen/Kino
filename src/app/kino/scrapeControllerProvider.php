<?php

    namespace kino;

    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use kino\scrapeHelpers;
    use kino\databaseHelpers;

    class scrapeControllerProvider implements ControllerProviderInterface {

        public function connect ( Application $app ) {

            $ctr = $app['controllers_factory'];

            $ctr->get( '/', function( Application $app ) {

                $url = 'http://www.rtl.lu/kultur/film-a-kino/programm/';

                $proxy = 'http://getcontents.herokuapp.com/?url=';

                $cinameData = file_get_contents( $proxy . $url );

                //$cinameData  = file_get_contents('data/index.html');

                $cinemaTable = ScrapeHelpers::getCinemaTable ( $cinameData  );
                $cinemas     = ScrapeHelpers::getCinemas     ( $cinemaTable );

                if (date ('N') == 3) { // Check if we are Wednesday
                    $crawlDates[] = date( 'Ymd', strtotime('today') );
                } else {
                    $crawlDates[] = date( 'Ymd', strtotime('last wednesday') );
                }

                $crawlDates[] = date( 'Ymd', strtotime('next wednesday') );

                foreach ($cinemas as $cinemaId => $cinemaName) {

                    foreach ($crawlDates as $crawlDate) {

                        self::getShowTimesByCinemaAndDate( $app, $cinemaId, $crawlDate );

                    }

                }

                return true;


            });

            return $ctr;

        }

        public function getShowTimesByCinemaAndDate ( $app, $cinemaId, $date ) {

            $url = 'http://www.rtl.lu/kultur/film-a-kino/programm/salle?salle=%d&seance=%d';

            $url = urlencode( sprintf( $url, $cinemaId, $date ) );

            $proxy = 'http://getcontents.herokuapp.com/?url=';

            $screeningData = file_get_contents( $proxy . $url );

            //$screeningData = file_get_contents( 'data/deprogramm.html' );

            $moviesPattern = '/\<div class="movie">(.*?)<div class="divider">/s';

            preg_match_all( $moviesPattern, $screeningData, $moviesMatches );

            $movies = $moviesMatches[1];

            $screeningTableRowPattern = '/<tr>(.*?)<\/tr>/';

            $screeningPattern = '/<td(.*?)<\/td>/';

            foreach ($movies as $key => $movieData) {

                $movie[ 'id' ] = ScrapeHelpers::getMovieId    ( $movieData );

                if ( !DatabaseHelpers::movieIdExists( $app, $movie[ 'id' ] ) ) {

                    $movie[ 'title' ] = ScrapeHelpers::getMovieTitle ( $movieData );

                    preg_match(
                        '/<\/a>&nbsp;\((.*?)\)<\/div>/',
                        $movieData,
                        $movieLanguage
                    );

                    $movie['language'] = $movieLanguage[1];

                    if ( strpos( $movie['language'], '3D' ) !== FALSE ) {

                        $movie['language'] = substr(
                            $movie['language'],
                            0,
                            strpos( $movie['language'], ', 3D' )
                        );

                        $movie['3d'] = true;

                    } else {

                        $movie['3d'] = false;

                    }

                    $url = 'http://www.rtl.lu/kultur/film-a-kino/programm/film?id=%d';

                    $url = urlencode( sprintf( $url, $movie['id'] ) );

                    $proxy = 'http://getcontents.herokuapp.com/?url=';

                    $movieMetaData = file_get_contents( $proxy . $url );

                    //$movieMetaData = file_get_contents( 'data/film.html' );

                    $movieMetaData = html_entity_decode( $movieMetaData );


                    $movie[ 'actors'         ] = ScrapeHelpers::getActors         ( $movieMetaData );
                    $movie[ 'duration'       ] = ScrapeHelpers::getDuration       ( $movieMetaData );
                    $movie[ 'genre'          ] = ScrapeHelpers::getGenre          ( $movieMetaData );
                    $movie[ 'synopsis'       ] = ScrapeHelpers::getSynopsis       ( $movieMetaData );
                    $movie[ 'ageRestriction' ] = ScrapeHelpers::getAgeRestriction ( $movieMetaData );
                    $movie[ 'posterURL'      ] = ScrapeHelpers::getPosterUrl      ( $movieMetaData );

                    DatabaseHelpers::saveMovie( $app, $movie );

                }

                preg_match_all($screeningTableRowPattern, $movieData, $screeningRows);

                $screeningRows = $screeningRows[1];

                unset( $screeningRows[0] );

                $screening[ 'movieId'  ] = $movie[ 'id' ];
                $screening[ 'cinemaId' ] = $cinemaId;

                foreach ($screeningRows as $key => $screeningRow) {

                    preg_match_all($screeningPattern, $screeningRow, $screenings);

                    $screenings = $screenings[0];

                    $weekDayCount = 0;

                    foreach ($screenings as $key => $screeningTime) {

                        $screeningTime = strip_tags($screeningTime);

                        if ( !empty( $screeningTime ) ) {

                            $screeningDate = date( 'Y-m-d', strtotime( $date + $weekDayCount ) );

                            $screening[ 'datetime' ] = $screeningDate . ' ' . $screeningTime;

                            //echo "$screeningDate >> $screeningTime $weekDayCount<br />";

                            //echo $movie[ 'id' ]. ' ' .$screeningDateTime;

                            if ( !DatabaseHelpers::screeningExists( $app, $screening ) ) {

                                DatabaseHelpers::saveScreening( $app, $screening );

                            }

                        }

                        $weekDayCount++;

                    }

                }

            }

        }

    }
