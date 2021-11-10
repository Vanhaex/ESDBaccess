<?php

namespace ESDBACCESS;

/**
 * Décrit les méthodes utilisées pour la classe ESDBaccess
 */
interface ESDBaccessInterface
{

    /**
     * Ouvre une connexion à la base de données, en vérifiant d'abord qu'elle est bien en route.
     *
     * @return bool
     */
    public function connectToDB() : bool;

    /**
     * Ferme une connexion à la base de données
     *
     * @return bool
     */
    public function disconnectToDB() : bool;

    /**
     * Interroge la base de données pour voir si elle est bien en route
     *
     * @return bool
     */
    public function pingToDB() : bool;

    /**
     * Execute une requête SELECT simple sur une table donnée en fonction des colonnes et des conditions souhaitées
     *
     * @param string $table
     * @param array|null $columns
     * @param string|null $condition
     */
    public function querySelect(string $table, array $columns = null, string $condition = null) : void;

    /**
     * Execute une requête INSERT simple sur une table donnée en fonction des colonnes et des conditions souhaitées
     *
     * @param string $table
     * @param array $columns
     * @param array $values
     * @param null $condition
     */
    public function queryInsert(string $table, array $columns, array $values, $condition = null) : void;

    /**
     * Retourne tous les résultats obtenus dans un tableau
     *
     * @return array
     */
    public function allResults() : ?array;

    /**
     * Retourne le prochain résultat obtenu
     *
     * @return mixed
     */
    public function thisResult();

    /**
     * Retourne le nombre de lignes qui sont affectées / concernées par la dernière requête SQL
     *
     * @return int
     */
    public function affectedRows() : int;

    /**
     * Retourne le nombre de lignes qui ont été récupérées par la dernière requête SQL
     *
     * @return int
     */
    public function numOfRows() : int;

    /**
     * Active le mode autocommit pour MySQL
     *
     * @param false $isActive
     * @return bool
     */
    public function ESDBautocommit(bool $isActive = false) : bool;

    /**
     * Mode transactionnel, permet de valider la requête. Ne fonctionne que si le mode autocommit est à false
     *
     * @return bool
     */
    public function ESDBcommit() : bool;

    /**
     * Mode transactionnel, ne valide pas la requête et revient en arrière. Ne fonctionne que si le mode autocommit est à false
     *
     * @return bool
     */
    public function ESDBrollback() : bool;


}
