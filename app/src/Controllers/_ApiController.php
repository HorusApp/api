<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DataAccess\_DataAccess;

final class _ApiController
{
    protected $dataaccess;
    
    public function __construct(_DataAccess $dataaccess) {
        $this->dataaccess = $dataaccess;
    }

    public function index(Request $request, Response $response, $args) {
        return $response->write('HorusApp API');
    }
}
