<?php

namespace App\Theatres;

use Exception;

class Kinepolis
{
    protected $data;
    protected $url;
    protected $html;

    public function source($url)
    {
        $this->url = $url;
    }

    public function scrape()
    {
        $result = [];

        $result['genres']     = self::getFilterAsArray('genre', $this->html);
        $result['languages']  = self::getFilterAsArray('version', $this->html);
        $result['categories'] = self::getFilterAsArray('category', $this->html);
        $result['formats']    = self::getFilterAsArray('format', $this->html);
        $result['types']      = self::getFilterAsArray('type', $this->html);
        $result['countries']  = self::getFilterAsArray('country', $this->html);

        foreach ($this->data['kinepolis_theaters'] as $theatre) {
            $theatres[$theatre['uuid']] = $theatre['title'];
        }

        $result['theatres'] = $theatres;

        $result['movies']     = [];
        $result['screenings'] = [];

        $movies = $this->data['kinepolis_movie_filter']['list'];

        foreach ($movies as $movie) {
            $movie = $movie['data'];

            $titles = [];
            foreach ($movie['variants'] as $language => $variant) {
                $titles[(int) $language] = $variant['title'];
            }

            $directors = [];
            if (isset($movie['director'])) {
                foreach ($movie['director'] as $directorArray) {
                    $directors[] = $directorArray['title'];
                }
            }
            $cast = [];
            if (isset($movie['cast'])) {
                foreach ($movie['cast'] as $castArray) {
                    $cast[] = $castArray['title'];
                }
            }
            $genres = [];
            if (isset($movie['genre'])) {
                foreach ($movie['genre'] as $genre) {
                    if (isset($result['genres'][(int) $genre])) {
                        $genres[] = (int) $genre;
                    }
                }
            }
            $categories = [];
            if (isset($movie['category'])) {
                foreach ($movie['category'] as $category) {
                    if (isset($result['categories'][(int) $category])) {
                        $categories[] = (int) $category;
                    }
                }
            }
            $country = null;
            if (isset($movie['country'])) {
                $country = (int) key($movie['country']);
            }
            $imdb = null;
            if (substr($movie['imdb'], 0, 2) == 'tt') {
                $imdb = $movie['imdb'];
            }
            $movie['uuid'] = (int) $movie['uuid'];

            $result['movies'][$movie['uuid']] = [
                'imdb'       => $imdb,
                'titles'     => $titles,
                'synopsis'   => $movie['small_synopsys'] ? $movie['small_synopsys'] : null,
                'country'    => $country,
                'directors'  => $directors,
                'cast'       => $cast,
                'genres'     => $genres,
                'categories' => $categories,
                'length'     => (int) $movie['length'] ? (int) $movie['length'] : null,
            ];

            $screenings = [];
            foreach ($movie['programmation'] as $screeningRaw) {
                $screening = [];
                $screening = [
                    'movie'    => $movie['uuid'],
                    'theatre'  => $screeningRaw['theater'],
                    'hall'     => self::getScreeingHall($screeningRaw['screen']),
                    'format'   => (int) $screeningRaw['format'],
                    'language' => self::getScreeningLanguage($screeningRaw['version']),
                    'time'     => strtotime($screeningRaw['date'].' '.$screeningRaw['time']),
                ];

                $screenings[] = $screening;
            }
            $result['screenings'] = array_merge($result['screenings'], $screenings);
        }
        $this->data = $result;
    }

    public function data()
    {
        return $this->data;
    }

    public function html()
    {
        return $this->html;
    }

    protected function getFilterAsArray($filterName, $data)
    {
        $filterRawRegex = '/filter-select-'.$filterName.'">(.*)<\/select/';
        $filterRegex    = '/(\d+)">([^<]*)<\//';

        if (!preg_match($filterRawRegex, $data, $filterRaw)) {
            throw new Exception("Couldn't find $filterName.");
        }

        if (!preg_match_all($filterRegex, $filterRaw[1], $filterArray)) {
            throw new Exception("Couldn't split up $filterName.");
        }
        $array = [];

        foreach ($filterArray[1] as $key => $id) {
            $array[$id] = $filterArray[2][$key];
        }

        return $array;
    }

    protected function getScreeningLanguage($version)
    {
        $languageIdRegex = '/(\d+)(?!.*\d)/';

        if (!preg_match($languageIdRegex, $version, $language)) {
            return null;
        }

        return (int) $language[1];
    }

    protected function getScreeingHall($screen)
    {
        $languageIdRegex = '/(\d+)(?!.*\d)/';

        if (!preg_match($languageIdRegex, $screen, $hall)) {
            return null;
        }

        return (int) $hall[1];
    }

    public function load()
    {
        $this->html = file_get_contents($this->url);

        $jsonRegex = '/<script>jQuery\.extend\(Drupal\.settings,(.*)\);<\/script>/';

        if (!preg_match($jsonRegex, $this->html, $match)) {
            throw new Exception('no data found');
        }

        $this->data = json_decode($match[1], true);
    }

    public function genres()
    {
        return $this->data['genres'];
    }

    public function languages()
    {
        return $this->data['languages'];
    }

    public function categories()
    {
        return $this->data['categories'];
    }

    public function formats()
    {
        return $this->data['formats'];
    }

    public function types()
    {
        return $this->data['types'];
    }

    public function countries()
    {
        return $this->data['countries'];
    }

    public function theatres()
    {
        return $this->data['theatres'];
    }

    public function movies()
    {
        return $this->data['movies'];
    }

    public function screenings()
    {
        return $this->data['screenings'];
    }
}
