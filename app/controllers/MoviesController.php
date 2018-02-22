<?php


/**
 * Created by PhpStorm.
 * User: santiagogg
 * Date: 21/02/2018
 * Time: 21:28
 */

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MoviesController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write('<h1>Movie Index</h1>');
    
        return $response;
    }
}