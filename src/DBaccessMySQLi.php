<?php

namespace DBACCESS;

use Psr\Log;

class DBaccessMySQLi implements DBaccessMySQLiInterface
{
    public $mysqli;

    public $host;
    public $port;
    public $user;
    public $password;
    public $database;

    public function setHost($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function setUser($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function setDatabase($database)
    {
        $this->database = $database;
    }

    public function initConnexion(DBaccessConnexionInterface $informations)
    {
        if(empty($host) || empty($port) || empty($user) || empty($password) || empty($database))
        {
            
        }
    }
}

?>