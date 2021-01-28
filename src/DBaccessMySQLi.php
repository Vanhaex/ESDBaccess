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

    /**
     * @var LoggerInterface
     */
    private $logger;

    private $host;
    private $port;
    private $user;
    private $password;
    private $database;

    private $result_store;

    private $err_code;
    private $err_string;

    private $result_object = true; // Résultats sous forme d'objet
    private $affected_rows;
    private $insert_id;
    private $num_of_rows;

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

        if(empty($this->host) || empty($this->user)){
            $this->logger->error("Impossible de se connecter à la base de données. Des informations sont manquantes");
        }

        if(!is_null($this->mysqli)){
            $this->close();
        }
        elseif(is_null($this->mysqli)){
            $this->mysqli = new mysqli();
            $this->mysqli->init(); // On initialise Mysqli avant toute chose
        }

        if($this->db_exceptions == true){
            mysqli_report(MYSQLI_REPORT_ERROR || MYSQLI_REPORT_STRICT); // On ajoute des exceptions
        }

        $connect = $this->mysqli->real_connect($this->host, $this->user, $this->password, $this->database, $this->port); // On peut donc se connecter

        if($connect == false) {
            $this->err_code = $this->mysqli->errno;
            $this->err_string = $this->mysqli->error;
            $this->logger->error("Impossible de se connecter à la base de données. L'une des informations est incorrecte");
        }
    
        return $connect;
    }

    public function reopen()
    {
        if(!is_null($this->logger)){
            $this->logger->info("Reconnexion à la base de données");
        }

        return $this->mysqli->ping();
    }

    public function close()
    {
        if(!is_null($this->logger)){
            $this->logger->info("Fermeture de la connexion à la base de données");
        }

        if(is_null($this->mysqli)){
            return true;
        }

        $this->mysqli->close();
        $this->mysqli = null;

        return true;
    }

    public function query(string $query)
    {
        if(!is_null($this->logger)){
            $this->logger->info("Execution de la requête SQL : " . $query);
        }

        $this->reset_mysqli(); // on vide les variables

        trim($query); // On retire les espaces en début et en fin pour faire du ménage

        $requete = $this->mysqli->real_query($query); // On peut donc executer la requête

        // Si la requête est bonne, on fait les traitements, sinon error
        if($requete == true){            
            if($this->mysqli->field_count){

                $result_store = $this->mysqli->store_result(); // Stockage du résultat

                // Objet ou tableau associatif sinon
                if($this->result_object == true){
                    while ($data = $result_store->fetch_object()){
                        array_push($this->$result_store, $data);
                    }
                }
                else{
                    while ($data = $result_store->fetch_assoc()){
                        array_push($this->$result_store, $data);
                    }
                }
                
            }
            else{
                $this->affected_rows = $this->mysqli->affected_rows;
                $this->insert_id = $this->mysqli->insert_id;
            }

            $this->num_of_rows = $this->mysqli->num_rows;

            $this->result_store->free();
        }
        else{
            $this->err_code = $this->mysqli->errno;
            $this->err_string = $this->mysqli->error;
            $this->logger->error("Erreur lors de l'éxecution de la requête SQL");
        }
    }

    public function preparedQuery(string $prepared_query, string $bind_type, ...$bind_data)
    {
        if(!is_null($this->logger)){
            $this->logger->info("Execution de la requête préparée : " . $prepared_query . "& params = [" . $bind_type . "] & données = [" . $bind_data . "]");
        }

        $this->reset_mysqli(); // On vide les variables

        trim($prepared_query); // On retire les espaces en début et en fin pour faire du ménage

        $stmt = $this->mysqli->prepare($prepared_query);

        if ($stmt == true) {
            $stmt->bind_param($bind_type[0], $bind_data[]);

            $execute_query = $stmt->execute();

            if ($execute_query == true) {

                if($this->mysqli->field_count){

                    $result_store = $stmt->result_metadata();

                    // Objet ou tableau associatif sinon
                    if($this->result_object == true){
                        while ($data = $result_store->fetch_object()){
                            array_push($this->$result_store, $data);
                        }
                    }
                    else{
                        while ($data = $result_store->fetch_assoc()){
                            array_push($this->$result_store, $data);
                        }
                    }
                }
                else{
                    $this->affected_rows = $this->mysqli->affected_rows;
                    $this->insert_id = $this->mysqli->insert_id;
                }
    
                $this->num_of_rows = $this->mysqli->num_rows;
    
                $this->result_store->free();
            }
            else {
                $this->logger->error("Erreur lors de l'éxecution de la requete préparée");
            }
        }
        else{
            $this->err_code = $this->mysqli->errno;
            $this->err_string = $this->mysqli->error;
            $this->logger->error("La requête préparée est incorrecte");
        }

        $stmt->close();
    }

    public function reset_mysqli()
    {
        // on vide les variables pour faire du ménage !
        $this->err_code = 0;
        $this->err_string = null;
        $this->numOfRows = 0;

    }

    public function getAllResults() {
        return $this->
    }
}

?>