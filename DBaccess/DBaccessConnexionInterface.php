<?php

namespace DBaccess;

/**
 * 
 * Décrit les fonctions pour les infos de connexion à la BDD
 * 
 */
interface DBaccessConnexionInterface {

    /**
     * Le nom de l'hôte pour la connexion
     * @param string $host
     * 
     * @return void
     */
    public function GetHost($host);

    /**
     * Le port pour la connexion
     * @param int $port
     * 
     * @return void
     */
    public function GetPort($port);

    /**
     * Le nom d'utilisateur pour la connexion
     * @param string $user
     * 
     * @return void
     */
    public function GetUser($user);

    /**
     * Le password pour la connexion
     * @param string $password
     * 
     * @return void
     */
    public function GetPassword($password);

    /**
     * Le nom de la base de données pour la connexion
     * @param string $database
     * 
     * @return void
     */
    public function GetDatabase($database);
}



?>