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

                $RTmovieData = Rottentomatoes::getMovieData ( $movie['title'] );

                $app['db']->insert(
                    'movies',
                    array(
                        //'idmovie'           =>  $RTmovieData['idRT'],
                        'idmovie'           =>  $movie['title'],
                        'title'             =>  $RTmovieData['title'],
                        'runtime'           =>  $RTmovieData['runtime'],
                        'synopsis'          =>  $RTmovieData['synopsis'],
                        'mpaa_rating'       =>  $RTmovieData['mpaa_rating'],
                        'RTcritics_score'   =>  $RTmovieData['RTcritics_score'],
                        'RTaudience_score'  =>  $RTmovieData['RTaudience_score'],
                        'posterSmall'       =>  $RTmovieData['posterSmall'],
                        'posterLarge'       =>  $RTmovieData['posterLarge'],
                        'idIMDB'            =>  $RTmovieData['idIMDB'],
                        'idRTL'             =>  $movie['id'],
                        'language'          =>  $movieLanguage,
                        '3d'                =>  $movie3D
                    )
                );

                $movieId = $RTmovieData['idRT'];

                foreach ($RTmovieData['actors'] as $actor) {

                    self::saveActorMovieRelation ( $app, $actor, $movieId );

                }

            }

        }

        static public function saveActorMovieRelation ( $app, $actor, $movieId ) {

            self::saveActor( $app, $actor );

            $app['db']->insert(
                'movieActor',
                array(
                    'fimovie'   =>  $movieId,
                    'fiactor'   =>  $actor['id']
                )
            );

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

            $movieId_query  = 'SELECT idRTL
                FROM movies
                WHERE idRTL = ?';

            $movieId = $app['db']->fetchColumn(
                $movieId_query,
                array(
                    (int) $movieId
                )
            );

            return $movieId;

        }

    }