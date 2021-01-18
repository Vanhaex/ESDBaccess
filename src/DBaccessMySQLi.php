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
        
    }

    /**
     * Les logs c'est bien !
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}

?>