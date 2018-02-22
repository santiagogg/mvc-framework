<?php
require __DIR__ . '/../vendor/autoload.php';

$container = new League\Container\Container;

$container->share('response', Zend\Diactoros\Response::class);
$container->share('request', function () {
    return Zend\Diactoros\ServerRequestFactory::fromGlobals(
        $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
    );
});

$container->share('emitter', Zend\Diactoros\Response\SapiEmitter::class);

$route = new League\Route\RouteCollection($container);

require __DIR__ . '/../app/routes.php';

$response = $route->dispatch($container->get('request'), $container->get('response'));

$container->get('emitter')->emit($response);