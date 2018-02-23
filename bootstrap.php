<?php

// Load our autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Use Dotenv to load environment variables from .env file
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

//Instantiate our Container
$container = new League\Container\Container;

//Response
$container->share('response', Zend\Diactoros\Response::class);

//Request
$container->share('request', function() {
    return Zend\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
});

//Emiter
$container->share('emitter', Zend\Diactoros\Response\SapiEmitter::class);

// Template Engine
$container->share('Twig', function() {
    // Specify our Twig templates location
    $twigLoader = new Twig_Loader_Filesystem(__DIR__ . '/app/views');
    
    return new Twig_Environment($twigLoader);
});

//PDO Connection
$container->share('PDO', function() {
    try {
        $host = getenv('DB_HOST');
        $database = getenv('DB_DATABASE');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        $conn = null;
    }
    
    return $conn;
});

//Inject dependencies to controller
$container->add(\Controllers\MoviesController::class)
    ->withArgument($container->get('Twig'))
    ->withArgument($container->get('PDO'));

//Instantiate our Router
$route = new League\Route\RouteCollection($container);

require_once __DIR__ . '/app/routes.php';

//dispatch the response
$response = $route->dispatch($container->get('request'), $container->get('response'));
$container->get('emitter')->emit($response);