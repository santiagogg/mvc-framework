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
        $movies = new Movies($this->conn);
        
        return $this->view($response, 'movies/index.twig', ['movies' => $movies->getMoviesOnShowWithVotes()]);
    }
    
    public function vote(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $movieId = $args['id'];
        $movies = new Movies($this->conn);
        $movies->saveVote($movieId);
        $movieUpdated = $movies->getVotesForMovie($movieId);
        $response->getBody()->write(json_encode($movieUpdated));
        
        return $response->withStatus(200);
    }
}