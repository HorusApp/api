<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Utils\_Encryption as Encryption;
use App\Controllers\_Controller;
use App\DataAccess\_DataAccess;
use \DateTime;

class _AuthController extends _Controller
{
   
    protected $user;

    public function __construct(_DataAccess $dataaccess) {
        parent::__construct($dataaccess);
    }

    public function login(Request $request, Response $response) {
        $request_data = $request->getParsedBody();
        $email = $request_data['email'];
        $password = $request_data['password'];
        
        if (!$email || !$password) {
            return $response->withStatus(400);
        }

        $encryption = new Encryption;

        if ($password) {
            $password = $encryption->encode($password);
            $request_data['password'] = $password;
        }

        if (!isset($this->user)) {
            $this->user = $this->dataaccess->get('users', [
                'email' => $email,
                'password' => $password
            ]);
        }
        
        if (isset($this->user)) {
            $timestamp = (new DateTime())->format('Y-m-dH:i:sP');
            $token = $encryption->encode($email . $password . $timestamp);


            $isUpdated = $this->dataaccess->update('users', $request_data, ['token' => $token]);

            if ($isUpdated) {
                $this->user['token'] = $token;

                return $token;
            }
        }

        return $response->withStatus(400);
    }
    
    public function logout(Request $request, Response $response) {    
        $request_data = $request->getParsedBody();

        if (!$request_data['token']) {
            return $response->withStatus(400);
        }
        
        if ($this->validateToken($request)) {
            $this->dataaccess->update('users', $request_data, ['token' => null]);
        }

        return true;
    }
    
    public function validateToken(Request $request) {
        $token = $request->getHeaderLine('token');
        $email = $request->getHeaderLine('email');

        if (!$email || !$token) {
            return false;
        }

        $user = $this->user;

        if (!$user) {
            $user = $this->dataaccess->get('users', [
                'email' => $email,
                'token' => $token,
            ]);

            $this->user = $user;
        }

        if (!$user || !$user['token'] || $user['token'] !== $token) {
            return false;
        }

        return true;
    }
    
    public function getAll(Request $request, Response $response, $args) {
        if ($this->validateToken($request)) {
            return parent::getAll($request, $response, $args);
        } else {
            return $response->withStatus(403);
        }                      
    }
    
    public function get(Request $request, Response $response, $args) {
        if ($this->validateToken($request) && isset($this->user)) {
            return parent::get($request, $response, $args);
        } else {
            return $response->withStatus(403);
        }           
    }
    
    public function add(Request $request, Response $response, $args) {
        if ($this->validateToken($request) && isset($this->user)) {
            return (parent::add($request, $response, $args));
        } else {
            return $response->withStatus(403);
        }
    }
    
    public function update(Request $request, Response $response, $args) {
        if ($this->validateToken($request) && isset($this->user)) {
            return (parent::update($request, $response, $args));
        } else {
            return $response->withStatus(403);
        }           
    }
    
    public function delete(Request $request, Response $response, $args) {
        if ($this->validateToken($request) && isset($this->user)) {
            return (parent::delete($request, $response, $args));
        } else {
            return $response->withStatus(403);
        }           
    }
}