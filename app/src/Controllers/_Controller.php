<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DataAccess\_DataAccess;

class _Controller
{
    protected $dataaccess;

    public function __construct(_DataAccess $dataaccess) {
        $this->dataaccess = $dataaccess;
    }

    public function getAll(Request $request, Response $response, $args) {
        $path = explode('/', $request->getUri()->getPath())[1];
        $arrparams = $request->getParams();

		return $response->write(json_encode($this->dataaccess->getAll($path, $arrparams)));
    }

    public function get(Request $request, Response $response, $args) {

        $path = explode('/', $request->getUri()->getPath())[1];

        $result = $this->dataaccess->get($path, $args);

        if ($result == null) {
            return $response->withStatus(404);
        } else {
            return $response->write(json_encode($result));
        }
    }

    public function add(Request $request, Response $response, $args) {
        $path = explode('/', $request->getUri()->getPath())[1];
        $request_data = $request->getParsedBody();

        $last_inserted_id = $this->dataaccess->add($path, $request_data);
        if ($last_inserted_id > 0) {
            $RequesPort = '';
		    
            if ($request->getUri()->getPort()!='') {
		        $RequesPort = '.'.$request->getUri()->getPort();
		    }

            $LocationHeader = $request->getUri()->getScheme().'://'.$request->getUri()->getHost().$RequesPort.$request->getUri()->getPath().'/'.$last_inserted_id;

            return $response ->withHeader('Location', $LocationHeader)
                            ->withStatus(201);
        } else {
            return $response->withStatus(403);
        }
    }

    public function update(Request $request, Response $response, $args) {
        $path = explode('/', $request->getUri()->getPath())[1];
        $request_data = $request->getParsedBody();

        $isupdated = $this->dataaccess->update($path, $args, $request_data);
        
        if ($isupdated) {
            return $response->withStatus(200);
        } else {
            return $response->withStatus(404);
        }
    }

    public function delete(Request $request, Response $response, $args) {
        $path = explode('/', $request->getUri()->getPath())[1];

        $isdeleted = $this->dataaccess->delete($path, $args);
        if ($isdeleted) {
            return $response->withStatus(204);
        } else {
            return $response->withStatus(404);
        }
    }
}
