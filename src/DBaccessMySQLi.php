<?php

namespace DBACCESS;

use mysqli;
use Psr\Log\LoggerInterface;

class DBaccessMySQLi implements DBaccessMySQLiInterface
{
    private $mysqli;    // object mysqli

    private $host;
    private $port;
    private $user;
    private $password;
    private $database;

    private $logger;    // object log

    public function initConnexion(DBaccessConnexionInterface $informations)
    {
        $this->setHost($informations->GetHost, $informations->GetPort);
        $this->setUser($informations->GetUser, $informations->GetPassword);
        $this->setDatabase($informations->GetDatabase);
    }

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

    public function open()
    {   
        if(!is_null($this->logger)){
            $this->logger->info("Connexion à la base de données");
        }

        if(empty($this->host) || empty($this->port) || empty($this->user) || empty($this->password) || empty($this->database)){
            $this->logger->error("Impossible de se connecter à la base de données. Des informations sont manquantes");
        }

        
    }
}

?>