<?php

/**
 * Created by PhpStorm.
 * User: santiagogg
 * Date: 21/02/2018
 * Time: 21:28
 */

namespace Controllers;

use Models\Movies;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MoviesController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response) {
        
        $movies = new Movies();
        
        return $this->view($response, 'movies/index.twig', ['movies' => $movies->onShow()]);
    }
    
    public function vote(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        
        $movieId = $args['id'];
        //TODO:persist data in db
        $vote = [
            'movie_id' => $movieId,
            'voted_at' => date('Y-m-d H:i:s')
        ];
        
        
        //TODO:get the total votes from db
        $movieUpdated = [
            'movie_id'    => $movieId,
            'last_voted'  => $vote['voted_at'],
            'total_votes' => 1
        ];
        
        $response->getBody()->write(json_encode($movieUpdated));
        
        return $response->withStatus(200);
    }
}