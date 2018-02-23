<?php

/**
 * Created by PhpStorm.
 * User: santiagogg
 * Date: 21/02/2018
 * Time: 21:28
 */

namespace Controllers;

use Models\Movies;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig_Environment;

class MoviesController extends BaseController
{
    protected $conn;
    
    /**
     * MoviesController constructor.
     *
     * @param \Twig_Environment $view
     * @param \PDO $conn
     */
    public function __construct(Twig_Environment $view, PDO $conn) {
        parent::__construct($view);
        $this->conn = $conn;
    }
    
    public function index(ServerRequestInterface $request, ResponseInterface $response) {
        $movies = new Movies();
        return $this->view($response, 'movies/index.twig', ['movies' => $movies->onShow()]);
    }
    
    public function vote(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        
        $movieId = $args['id'];
        $time_stamp = date('Y-m-d H:i:s');
        
        //persist data in db
        $sql = "INSERT INTO votes (movie_id, voted_at) VALUES (:movie_id, :voted_at)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':movie_id' => $movieId,
            ':voted_at' => $time_stamp
        ]);
        
        //get the total_votes / last_votes from db
        $sql = "select movie_id, count(*) total_votes, MAX(voted_at) last_voted from votes where movie_id = :movie_id group by movie_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':movie_id' => $movieId
        ]);
        $movieUpdated = $stmt->fetch();
        
        
        $response->getBody()->write(json_encode($movieUpdated));
        
        return $response->withStatus(200);
    }
}