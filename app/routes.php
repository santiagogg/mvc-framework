<?php

$route->map('GET', '/movies', 'Controllers\MoviesController::index');
$route->map('POST', '/movies/{id}/vote', 'Controllers\MoviesController::vote');
