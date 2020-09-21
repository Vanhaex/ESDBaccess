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
     */
    public function GetPort($port);

    /**
     * Le nom d'utilisateur pour la connexion
     * @param string $user
     * 
     */
    public function GetUser($user);

    /**
     * Le password pour la connexion
     * @param string $password
     * 
     */
    public function GetPassword($password);

    /**
     * Le nom de la base de données pour la connexion
     * @param string $database
     * 
     */
    public function GetDatabase($database);
}



?>