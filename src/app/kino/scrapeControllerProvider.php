<?php

    namespace kino;

    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use kino\rottentomatoes;
    use kino\rtl;
    use kino\helpers;

    class scrapeControllerProvider implements ControllerProviderInterface {

        public function connect ( Application $app ) {

            $ctr = $app['controllers_factory'];

            $ctr->get( '/', function( Application $app ) {

                helpers::deleteScreenings( $app );

                $url = 'http://kultur.rtl.lu/kino/deprogramm/';

                $proxy = 'http://getcontents.herokuapp.com/?url=';

                $cinameData = file_get_contents( $proxy . $url );

                $app['moviePatterns'] = array (
                    'id'        =>  '/kino\/deprogramm\/film\?id=(.*?)&seance/',
                    'title'     =>  '/\" class="title">(.*?)<\/a>/',
                    'language'  =>  '/<\/a>&nbsp;(.*?)<\/div>/'
                );

                $cinemaPatterns = array(
                    'cinemasTable'  =>  '/\<table width="98(.*?)<\/table/s',
                    'cinemaNames'   =>  '/\<td width="32%">(.*?) <span/',
                    'cinemaIds'     =>  '/\?salle=(.*)&seance=today">/'
                );

                preg_match($cinemaPatterns['cinemasTable'], $cinameData, $cinemaMatches);

                $cinemaTable = $cinemaMatches[1];

                preg_match_all($cinemaPatterns['cinemaNames'], $cinemaTable, $cinemaNameMatches);

                $cinemaNames = $cinemaNameMatches[1];

                preg_match_all($cinemaPatterns['cinemaIds'], $cinemaTable, $cinemaIdMatches);

                $cinemaIds = $cinemaIdMatches[1];

                foreach( $cinemaIds as $arrayKey => $cinemaID ) {
                    $cinemas[ $cinemaID ] = $cinemaNames[ $arrayKey ];
                }

                if (date ('N') == 3) { // Check if we are Wednesday
                    $crawlDates[] = date( 'Ymd', strtotime('today') );
                } else {
                    $crawlDates[] = date( 'Ymd', strtotime('last wednesday') );
                }

                $crawlDates[] = date( 'Ymd', strtotime('next wednesday') );

                foreach ($cinemas as $cinemaId => $cinemaName) {

                    foreach ($crawlDates as $crawlDate) {

                        RTL::getShowTimesByCinemaAndDate ( $app, $cinemaId, $crawlDate );

                    }

                }

                return true;

            });

            return $ctr;

        }

    }
