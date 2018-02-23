<?php
/**
 * Created by PhpStorm.
 * User: santiagogg
 * Date: 22/02/2018
 * Time: 23:29
 */

namespace Models;

use GuzzleHttp\Client;
use PDO;

class Movies
{
    protected $conn;
    
    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }
    
    public function getMoviesOnShowFromAPI() {
        $last_thursday = (date("Y-m-d", strtotime("last Thursday")));
        $next_wednesday = date('Y-m-d', strtotime("last Thursday + 6 days"));
        
        $client = new Client(['base_uri' => 'http://api.themoviedb.org/']);
        $query = [
            'primary_release_date.gte' => $last_thursday,
            'primary_release_date.lte' => $next_wednesday,
            'api_key'                  => getenv('THEMOVIEDB_API_KEY')
        ];
        $body = $client->get('/3/discover/movie/', ['query' => $query])->getBody();
        
        return json_decode($body, true)['results'];
    }
    
    public function getMoviesOnShowWithVotes() {
        
        $votes = $this->getVotesForAllMovies();
        
        $movies = array_map(function($movie) use ($votes) {
            $basic = [
                'id'           => $movie['id'],
                'title'        => $movie['title'],
                'img_src'      => 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'],
                'overview'     => $movie['overview'],
                'release_date' => $movie['release_date']
            ];
            $i = array_search($movie['id'], array_column($votes, 'movie_id'));
            if ($i !== false) {
                $basic['total_votes'] = $votes[$i]['total_votes'];
                $basic['last_voted'] = $votes[$i]['last_voted'];
            } else {
                $basic['total_votes'] = 0;
                $basic['last_voted'] = 'never';
            }
            return $basic;
        }, $this->getMoviesOnShowFromAPI());
        
        return $movies;
    }
    
    public function saveVote($movieId) {
        $time_stamp = date('Y-m-d H:i:s');
        $sql = "INSERT INTO votes (movie_id, voted_at) VALUES (:movie_id, :voted_at)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':movie_id' => $movieId,
            ':voted_at' => $time_stamp
        ]);
    }
    
    public function getVotesForMovie($movieId) {
        //get the total_votes / last_votes from db
        $sql = "select movie_id, count(*) total_votes, MAX(voted_at) last_voted from votes where movie_id = :movie_id group by movie_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':movie_id' => $movieId
        ]);
        
        return $stmt->fetch();
    }
    
    public function getVotesForAllMovies() {
        //get the total_votes / last_votes from db
        $sql = "select movie_id, count(*) total_votes, MAX(voted_at) last_voted from votes group by movie_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);;
    }
}