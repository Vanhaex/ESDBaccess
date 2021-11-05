<?php

namespace Framework\ESDBaccess;

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
     * @param array $columns
     * @param string $table
     * @param string $condition
     * @param bool $where
     */
    public function querySelect(string $table, $condition = null, array $columns = null) : void;

    /**
     * Execute une requête INSERT simple sur une table donnée en fonction des colonnes et des conditions souhaitées
     *
     * @param array $columns
     * @param string $table
     * @param string $condition
     * @param bool $where
     */
    public function queryInsert(string $table, array $columns, array $values, $condition = null) : void;


    /**
     * Execute une requête SELECT préparée sur une table donnée en fonction des colonnes et des conditions souhaitées. Le bind type doit être dans la même ordre que les variables données en condition
     *
     * @param array $columns
     * @param string $table
     * @param string $condition
     * @param string $bind_type
     * @param array $bind_data
     */
    public function preparedQuerySelect(array $columns, string $table, string $condition, string $bind_type, array $bind_data) : void;


    /**
     * Execute une requête INSERT préparée sur une table donnée en fonction des colonnes et des conditions souhaitées. Le bind type doit être dans la même ordre que les variables données en condition
     *
     * @param array $columns
     * @param array $values
     * @param string $table
     * @param string $condition
     * @param string $bind_type
     * @param array $bind_data
     */
    public function preparedQueryInsert(array $columns, array $values, string $table, string $condition, string $bind_type, array $bind_data) : void;


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

}
