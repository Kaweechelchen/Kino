<?php

    namespace kino;

    use Silex\Application;

	class Rottentomatoes {

        static public function getMovieData ( $app, $movieName ) {

            sleep(1); // wait a second ;)

            $RTurl =      'http://api.rottentomatoes.com/api/public/v1.0/movies.json?apikey='
                        . $app['RT_api_key']
                        . '&page_limit=1'
                        . '&q='
                        . urlencode( $movieName );

            //$RTurl = 'http://localhost:8888/movies.json';

            $RTdata = json_decode( file_get_contents( $RTurl ), true );

            if ( $RTdata['total'] != 0 ) {

                $movieData = $RTdata['movies'][0];

                unset($actors);

                foreach ($movieData['abridged_cast'] as $key => $actor) {

                    $actors[$key] = array (
                        'name'  =>  $actor['name'],
                        'id'    =>  $actor['id']
                    );

                }

                $movie = array (
                    'title'             =>  $movieData['title'],
                    'mpaa_rating'       =>  $movieData['mpaa_rating'],
                    'runtime'           =>  $movieData['runtime'],
                    'RTcritics_score'   =>  $movieData['ratings']['critics_score'],
                    'RTaudience_score'  =>  $movieData['ratings']['audience_score'],
                    'synopsis'          =>  $movieData['synopsis'],
                    'posterSmall'       =>  $movieData['posters']['detailed'],
                    'posterLarge'       =>  $movieData['posters']['original'],
                    'idRT'              =>  $movieData['id'],
                    'actors'            =>  $actors
                );

                if ( isset( $movieData['alternate_ids']['imdb'] ) ) {

                    $movie['idIMDB']    =   $movieData['alternate_ids']['imdb'];
                }

            } else {

                $movie = array(
                    'title'             =>  $movieName,
                    'mpaa_rating'       =>  '',
                    'runtime'           =>  0,
                    'RTcritics_score'   =>  0,
                    'RTaudience_score'  =>  0,
                    'synopsis'          =>  '',
                    'posterSmall'       =>  '',
                    'posterLarge'       =>  '',
                    'idRT'              =>  0,
                    'idIMDB'            =>  0,
                    'actors'            =>  array()
                );

            }

            error_log($RTdata['total']);

            return $movie;

        }

    }
