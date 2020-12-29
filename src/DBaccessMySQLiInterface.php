<?php

namespace DBACCESS;

interface DBaccessMySQLiInterface{

    public function initConnexion(DBaccessConnexionInterface $informations); // Initialise la connexion à la bdd

    public function setHost($host, $port);          // Initialise l'host et le port pour la connexion
    public function setUser($user, $password);      // Initialise le login et mdp pour la connexion
    public function setDatabase($database);

    public function open(): void;                   // Ouvre la connexion
    public function reopen(): void;                 // Relance la connexion si besoin
    public function close(): void;                  // Ferme la connexion

    public function query();                        // Requête SQL simple
    public function preparedQuery();                // Requête préparée

    public function getAllResults();                // Récupérer tous les résultats sous forme d'array
    public function getNextResult();                // Récupérer le prochain résultat sous forme d'array
    public function getNumberResults();             // Récupérer le nombre de résultats obtenus
    
}

?>