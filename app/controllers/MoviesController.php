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
}