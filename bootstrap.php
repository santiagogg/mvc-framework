<?php

// Load our autoloader
require_once __DIR__.'/vendor/autoload.php';

//
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

//Instantiate our Container
$container = new League\Container\Container;

$container->share('response', Zend\Diactoros\Response::class);
$container->share('request', function () {
    return Zend\Diactoros\ServerRequestFactory::fromGlobals(
        $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
    );
});

$container->share('emitter', Zend\Diactoros\Response\SapiEmitter::class);


//
$container->share('Twig', function () {
    // Specify our Twig templates location
    $twigLoader = new Twig_Loader_Filesystem(__DIR__.'/app/views');
    
    return new Twig_Environment($twigLoader);
});

//Inject dependencies to controller

$container->add(\Controllers\MoviesController::class)
    ->withArgument($container->get('Twig'));


//Instantiate our Router
$route = new League\Route\RouteCollection($container);

require_once __DIR__ . '/app/routes.php';

//dispatch the response
$response = $route->dispatch($container->get('request'), $container->get('response'));
$container->get('emitter')->emit($response);