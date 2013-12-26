<?php

    namespace kino;

    use Silex\Application;

    class Helpers {

        static public function saveMovie ( $app, $movie ) {

            $movieId = self::movieIdExists( $app, $movie['id'] );

            if ( !$movieId)  {

                $movieLanguage = substr( $movie['language'], 1, -1 );

                $movie3D = false;

                if ( strpos( $movieLanguage, '3D' ) ) {

                    $movie3D = true;

                    $movieLanguage = substr( $movieLanguage, 0, -4 );

                }

                $RTmovieData = Rottentomatoes::getMovieData ( $app, $movie['title'] );

                $movieData = array(
                    'idmovie'           =>  $RTmovieData['idRT'],
                    'title'             =>  $RTmovieData['title'],
                    'runtime'           =>  $RTmovieData['runtime'],
                    'synopsis'          =>  $RTmovieData['synopsis'],
                    'mpaa_rating'       =>  $RTmovieData['mpaa_rating'],
                    'RTcritics_score'   =>  $RTmovieData['RTcritics_score'],
                    'RTaudience_score'  =>  $RTmovieData['RTaudience_score'],
                    'posterSmall'       =>  $RTmovieData['posterSmall'],
                    'posterLarge'       =>  $RTmovieData['posterLarge'],
                    'idRTL'             =>  $movie['id'],
                    'language'          =>  $movieLanguage,
                    '3d'                =>  $movie3D
                );

                if ( isset ( $RTmovieData['idIMDB'] ) ) {
                    $movieData['idIMDB']    =  $RTmovieData['idIMDB'];
                }

                $app['db']->insert(
                    'movies',
                    $movieData
                );

                $movieId = $RTmovieData['idRT'];

                self::saveActorsMovieRelation ( $app, $RTmovieData['actors'], $movieId );

            }

            return $movieId;

        }

        static public function saveActorsMovieRelation ( $app, $actors, $movieId ) {

            foreach ( $actors as $actor ) {

                self::saveActor( $app, $actor );

                $app['db']->insert(
                    'movieActor',
                    array(
                        'fimovie'   =>  $movieId,
                        'fiactor'   =>  $actor['id']
                    )
                );

            }

        }

        static public function saveActor ( $app, $actor ) {

            $actorExists_query  = 'SELECT idactor
                FROM actors
                WHERE idactor = ?';

            $actorExists = $app['db']->fetchColumn(
                $actorExists_query,
                array(
                    (int) $actor['id']
                )
            );

            if ( !$actorExists ) {

                $app['db']->insert(
                    'actors',
                    array(
                        'idactor'   =>  $actor['id'],
                        'name'      =>  $actor['name']
                    )
                );

            }

        }

        static public function movieIdExists ( $app, $movieId ) {

            $movieIdExists_query  = 'SELECT idRTL
                FROM movies
                WHERE idRTL = ?';

            $movieIdExists = $app['db']->fetchColumn(
                $movieIdExists_query,
                array(
                    (int) $movieId
                )
            );

            return $movieIdExists;

        }

        static public function deleteScreenings ( $app ) {

            $count = $app['db']->executeUpdate(
                'DELETE FROM screenings'
            );

            $count += $app['db']->executeUpdate(
                'DELETE FROM showtimes'
            );

            return $count;

        }

        static public function saveScreening ( $app, $movieId, $cinemaId, $showtime ) {

            $showtimeId = self::getShowtimeId ( $app, $showtime );

            $app['db']->insert(
                'screenings',
                array(
                    'fimovie'       =>  $movieId,
                    'fishowtime'    =>  $showtimeId,
                    'ficinema'      =>  $cinemaId
                )
            );

        }

        static public function getShowtimeId ( $app, $showtime ) {

            $showtimeId_query  = 'SELECT idshowtime
                FROM showtimes
                WHERE datetime = ?';

            $showtimeId = $app['db']->fetchColumn(
                $showtimeId_query,
                array(
                    $showtime
                )
            );

            if ( !$showtimeId ) {

                $app['db']->insert(
                    'showtimes',
                    array(
                        'datetime'      =>  $showtime
                    )
                );

                $showtimeId = $app['db']->lastInsertId();

            }

            return $showtimeId;

        }

    }