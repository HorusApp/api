<?php

use App\Controllers\_ApiController;
use App\Controllers\_Controller;
use App\Controllers\_AuthController;
use App\Controllers\_UserController;
use App\Controllers\_VideoController;

use App\DataAccess\_DataAccess;

$container = $app->getContainer();

// Database
$container['pdo'] = function ($c) {
    $settings = $c->get('settings')['pdo'];

    return new PDO($settings['dsn'], $settings['username'], $settings['password']);
};

// DataAccess
$container['App\DataAccess\_DataAccess'] = function ($c) {
    $localtable = $c->get('settings')['localtable'] != '' ? $c->get('settings')['localtable'] : '';
    return new _DataAccess($c->get('pdo'), $localtable);
};

// Controllers
$container['App\Controllers\_ApiController'] = function ($c) {
    return new _ApiController($c->get('App\DataAccess\_DataAccess'));
};

$container['App\Controllers\_Controller'] = function ($c) {
    return new _Controller($c->get('App\DataAccess\_DataAccess'));
};

$container['App\Controllers\_AuthController'] = function ($c) {
    return new _AuthController($c->get('App\DataAccess\_DataAccess'));
};

$container['App\Controllers\_UserController'] = function ($c) {
    return new _UserController($c->get('App\DataAccess\_DataAccess'));
};

$container['App\Controllers\_VideoController'] = function ($c) {
    return new _VideoController($c->get('App\DataAccess\_DataAccess'));
};