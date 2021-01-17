<?php

namespace DBACCESS;

/**
 * Classe abstraite qui implémente les méthodes pour les infos de connexion
 * 
 */
class DBaccessConnexion implements DBaccessConnexionInterface{

    private $host;
    private $port;
    private $user;
    private $password;
    private $database;

    public function __construct(string $host, int $port, string $user, string $password, string $database)
    {
        
    }

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