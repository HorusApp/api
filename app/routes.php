<?php

use App\Controllers\_ApiController;
use App\Controllers\_Controller;
use App\Controllers\_AuthController;
use App\Controllers\_UserController;
use App\Controllers\_VideoController;

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, token, email')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});


$app->get('/', _ApiController::class.':index');

$app->post('/login', _AuthController::class.':login');

$app->group('/users', function () {
    $this->get   ('/{id:[0-9]+}', _UserController::class.':get');
    $this->post  ('',             _UserController::class.':add');
    $this->put   ('/{id:[0-9]+}', _UserController::class.':update');
    $this->delete('/{id:[0-9]+}', _UserController::class.':delete');
});

$app->group('/videos', function () {
    $this->get   ('',             _VideoController::class.':getAll');
    $this->get   ('/{id:[0-9]+}', _VideoController::class.':get');
    $this->post  ('',             _VideoController::class.':add');
    $this->put   ('/{id:[0-9]+}', _VideoController::class.':update');
    $this->delete('/{id:[0-9]+}', _VideoController::class.':delete');
});