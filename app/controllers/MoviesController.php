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

class MoviesController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response) {
        $body = $response->getBody();
        $body->write($this->view->render('movies/index.twig', ['title' => 'Index of Movies']));
        
        return $response->withBody($body);
    }
}