<?php

namespace App\DataAccess;

use PDO;

class _DataAccess
{
    private $pdo;
    
    private $maintable;
    
    public function __construct(PDO $pdo, $table) {
        $this->pdo       = $pdo;
        $this->maintable = $table;
    }

    public function getAll($path, $arrparams) {
        $table = $this->maintable != '' ? $this->maintable : $path;

        $orderby = "";
        foreach ($arrparams as $key => $value) {
        	if ($key = "sort") {
        		$orderby = " ORDER BY " . $value;
        		break;
        	}
        }

        $stmt = $this->pdo->prepare('SELECT * FROM '.$table.$orderby);
        $stmt->execute();
        if ($stmt) {
            $result = array();
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $result[] = $row;
            }
        } else {
        	$result = null;
        }

        return $result;
    }

    public function get($path, $args) {
        $table = $this->maintable != '' ? $this->maintable : $path;

        $wheres = [];

        foreach($args as $key => $value){
            array_push($wheres, $key . ' = :' . $key);
        }

        $sql = "SELECT * FROM ". $table . ' WHERE ' . implode(' AND ', $wheres);

        $stmt = $this->pdo->prepare($sql);
        
        // bind the key
        foreach($args as $key => $value) {
            $stmt->bindValue(':' . $key, $args[$key]);
        }

        $stmt->execute();

        if ($stmt) {
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        } else {
            $result = null;
        }

        return $result;
    }

    public function add($path, $request_data) {
        $table = $this->maintable != '' ? $this->maintable : $path;

        if ($request_data == null) {
            return false;
        }

        $columnString = implode(',', array_flip($request_data));
        $valueString = ":".implode(',:', array_flip($request_data));

        $sql = "INSERT INTO " . $table . " (" . $columnString . ") VALUES (" . $valueString . ")";
        $stmt = $this->pdo->prepare($sql);

        foreach($request_data as $key => $value){
            $stmt->bindValue(':' . $key, $request_data[$key]);
        }

        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    public function update($path, $args, $request_data) {
        $table = $this->maintable != '' ? $this->maintable : $path;

        // if no data to update or not key set = return false
        if ($request_data == null || count($args) === 0) {
            return false;
        }

        $sets = 'SET ';
        
        foreach($request_data as $key => $value){
            $sets = $sets . $key . ' = :' . $key . ', ';
        }
        
        $sets = rtrim($sets, ", ");

        $wheres = [];

        foreach($args as $key => $value){
            array_push($wheres, $key . ' = :' . $key);
        }

        $sql = "UPDATE ". $table . ' ' . $sets . ' WHERE ' . implode(' AND ', $wheres);

        $stmt = $this->pdo->prepare($sql);

        foreach($request_data as $key => $value){
            $stmt->bindValue(':' . $key, $request_data[$key]);
        }
        
        // bind the key
        foreach($args as $key => $value) {
            $stmt->bindValue(':' . $key, $args[$key]);
        }

        $stmt->execute();

      	return ($stmt->rowCount() == 1) ? true : false;
    }

    public function delete($path, $args) {
        $table = $this->maintable != '' ? $this->maintable : $path;

        $sql = "DELETE FROM ". $table . ' WHERE ' . implode(',', array_flip($args)) . ' = :' . implode(',', array_flip($args));
        
        $stmt = $this->pdo->prepare($sql);
        
        // bind the key
        $stmt->bindValue(':' . implode(',', array_flip($args)), implode(',', $args));

        $stmt->execute();

      	return ($stmt->rowCount() > 0) ? true : false;
    }
}
