<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\_AuthController;
use App\DataAccess\_DataAccess;

class _VideoController extends _AuthController
{
    public function __construct(_DataAccess $dataaccess) {
        parent::__construct($dataaccess);
    }

    public function add(Request $request, Response $response, $args) {
        if ($this->validateToken($request)) {
            $request_data = $request->getParsedBody();
            
            $this->user = $this->dataaccess->get('users', [
                'token' => $request->getHeaders()['HTTP_TOKEN'][0],
            ]);

            $request_data['user_id'] = $this->user['id'];

            return $this->dataaccess->add('videos', $request_data);
        } else {
            return $response->withStatus(403);
        }
    }
}