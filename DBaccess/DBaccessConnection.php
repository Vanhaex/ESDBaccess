<?php

namespace DBaccess;

/**
 * Classe abstraite qui implémente les méthodes pour les infos de connexion
 * 
 */
class DBaccessConnection implements DBaccessConnexionInterface{

    public $host;
    public $port;
    public $user;
    public $password;
    public $database;

    public function GetHost($host)
    {
        $this->host = $host;
    }

    public function GetPort($port)
    {
        $this->port = $port;
    }

    public function GetUser($user)
    {
        $this->user = $user;
    }

    public function GetPassword($password)
    {
        $this->password = $password;
    }

    public function GetDatabase($database)
    {
        $this->database = $database;
    }
}

?>