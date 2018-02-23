<?php
/**
 * Created by PhpStorm.
 * User: santiagogg
 * Date: 22/02/2018
 * Time: 23:29
 */

namespace Models;

use GuzzleHttp\Client;

class Movies
{
    public function onShow() {
        $last_thursday = (date("Y-m-d", strtotime("last Thursday")));
        $next_wednesday = date('Y-m-d', strtotime("last Thursday + 6 days"));
        
        $client = new Client(['base_uri' => 'http://api.themoviedb.org/']);
        $query = [
            'primary_release_date.gte' => $last_thursday,
            'primary_release_date.lte' => $next_wednesday,
            'api_key'                  => getenv('THEMOVIEDB_API_KEY')
        ];
        
        $body = $client->get('/3/discover/movie/', ['query' => $query])->getBody();
        
        $movies = array_map(function($movie) {
            return [
                'id'          => $movie['id'],
                'title'       => $movie['title'],
                'img_src' => 'https://image.tmdb.org/t/p/w500/'.$movie['poster_path'],
                'overview'    => $movie['overview'],
                'release_date'    => $movie['release_date'],
            
            ];
        }, json_decode($body, true)['results']);
        return $movies;
    }
}