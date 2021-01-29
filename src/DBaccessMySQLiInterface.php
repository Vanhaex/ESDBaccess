<?php

namespace DBACCESS;

use Psr\Log\LoggerAwareInterface;

/**
 * 
 * Décrit les fonctions principales principales MySQLi (connexion, requêtes, résultats, ...)
 * 
 */
interface DBaccessMySQLiInterface extends LoggerAwareInterface{

    /**
     * Initialise la connexion à la base de données
     * 
     * @param $informations
     */
    public function initConnexion(DBaccessConnexionInterface $informations); // Initialise la connexion à la bdd

    /**
     * Retourne l'hôte et le port pour la connexion à la base de données
     * 
     * @param $host
     * @param $port
     */
    public function setHost(string $host, int $port);

    /**
     * Initialise le login et mdp pour la connexion
     * 
     * @param $user
     * @param $password
     */
    public function setUser(string $user, string $password);

    /**
     * Initialise le login et mdp pour la connexion
     * 
     * @param $database
     */
    public function setDatabase(string $database);

    /**
     * Ouvre la connexion
     */
    public function open();

    /**
     * Relance la connexion si besoin
     */
    public function reopen();

    /**
     * Ferme la connexion
     */
    public function close();

    /**
     * Fonction qui éxecute une requête SQL simple
     */
    public function query(string $query);

    /**
     * Fonction qui execute une requête préparée
     */
    public function preparedQuery(string $prepared_query, string $bind_type, ...$bind_data);

    /**
     * Récupère tous les résultats sous forme d'array
     */
    public function getAllResults();

    /**
     * Récupère le prochain résultat sous forme d'array
     */
    public function getNextResult();

    /**
     * Retourne le nombre de lignes obtenues lors de la dernière requête
     */
    public function getNumberResults();

    /**
     * Retourne Les lignes qui ont été traitées lors de la dernière requête
     */
    public function getAffectedRows();

    /**
     * Retourne l'ID de la dernière ligne
     */
    public function getLastID();

    /**
     * Retourne le code d'erreur MySQL
     */
    public function getErrorCode();

    /**
     * Retourne le message d'erreur MySQL
     */
    public function getErrorString();
    
    /**
     * Vide les variables utilisées par les requêtes
     */
    public function reset_mysqli();
}

?>