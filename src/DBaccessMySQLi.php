<?php

namespace DBACCESS;

use mysqli;
use Psr\Log\LoggerInterface;

class DBaccessMySQLi implements DBaccessMySQLiInterface
{
    /**
     * @var \mysqli
     */
    private $mysqli;

    private $host;
    private $port;
    private $user;
    private $password;
    private $database;

    /**
     * @var LoggerInterface
     */
    private $logger;

    private $db_exceptions = true;

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

        if($this->db_exceptions == true){
            mysqli_report(MYSQLI_REPORT_ERROR || MYSQLI_REPORT_STRICT);
        }

        $this->mysqli->init(); // On initialise Mysqli avant toute chose

        $connect = $this->mysqli->real_connect($this->host, $this->user, $this->password, $this->database, $this->port); // On peut donc se connecter

        if($connect == false) {
            $this->logger->error("Impossible de se connecter à la base de données. L'une des informations est incorrecte");
        }

    }

    public function reopen()
    {
        if(!is_null($this->logger)){
            $this->logger->info("Reconnexion à la base de données");
        }

        $this->mysqli->ping();
    }

    public function close()
    {
        if(!is_null($this->logger)){
            $this->logger->info("Fermeture de la connexion à la base de données");
        }

        $this->mysqli->close();
    }

    public function query(string $query)
    {
        if(!is_null($this->logger)){
            $this->logger->info("Execution de la requête SQL : " . $query);
        }

        trim($query); // On retire les espaces en début et en fin pour faire du ménage

        if(!empty($query)){

            $requete = $this->mysqli->query($query); // On peut donc executer la requête

            $result = $this->mysqli->store_result();

            if($requete == false){
                $this->logger->error("Erreur lors de l'éxecution de la requête SQL");
            }
        }
        else{
            if(!is_null($this->logger)){
                $this->logger->error("La requête n'existe pas ! Impossible donc de l'éxecuter");
            }
        }
    }
}

?>