<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Utils\_Encryption as Encryption;
use App\Controllers\_AuthController;
use App\DataAccess\_DataAccess;

class _UserController extends _AuthController
{
    public function __construct(_DataAccess $dataaccess) {
        parent::__construct($dataaccess);
    }

    public function checkUser(Request $request, $args) {
        $token = $request->getHeaderLine('token');
        $userId = $this->user['id'];

        if (!$userId) {
            $userId = $this->dataaccess->get('users', ['token' => $token])['id'];
        }

        return $userId == $args['id'];
    }
    
    public function add(Request $request, Response $response, $args) {
        $request_data = $request->getParsedBody();
        $encryption = new Encryption;

        if (!$this->dataaccess->get('users', ['email' => $request_data['email']]) && $request_data['password']) {
            $request_data['password'] = $encryption->encode($request_data['password']);
            return $this->dataaccess->add('users', $request_data);
        } else {
            return $response->withStatus(403);
        }
    }

    public function get(Request $request, Response $response, $args) {
        if ($this->checkUser($request, $args)) {
            return parent::get($request, $response, $args);
        }

        return $response->withStatus(403);
    }
    
    public function update(Request $request, Response $response, $args) {
        var_dump($request_data);

        if ($this->checkUser($request, $args)) {
            return parent::update($request, $response, $args);
        }

        return $response->withStatus(403);
    }
    
    public function delete(Request $request, Response $response, $args) {
        if ($this->checkUser($request, $args)) {
            return parent::delete($request, $response, $args);
        }

        return $response->withStatus(403);
    }
}