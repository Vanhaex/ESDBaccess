<?php

namespace Framework\ESDBaccess;

use mysqli;

class ESDBaccess implements ESDBaccessInterface
{
    // Les codes d'erreurs sont définis ici
    private const MISSING_PARAMETER = -1;
    private const CONNECT_ERROR = -2;
    private const DISCONNECT_ERROR = -3;
    private const MISSING_COLUMNS_DATABASE = -4;
    private const MISSING_TABLE_DATABASE = -5;
    private const MISSING_VALUES_DATABASE = -6;
    private const QUERY_SELECT_ERROR = -7;
    private const NEXT_RESULT_NOT_OBJECT = -8;
    private const ERROR_ORDER_VALUES = -9;

    // Les paramètres de connexion à la bdd
    private $host;
    private $port;
    private $user;
    private $password;
    private $database;

    // L'objet MySQLi
    private $sql;

    // les variables qui sont les métadonnées des requêtes
    private $results;
    private $affectedRows;
    private $numOfRows;

    /**
     * @inheritDoc
     */
    public function __construct(string $host, string $user, string $password, string $database, int $port)
    {
        // On vérifie d'abord si aucun paramètre n'a été oublié
        foreach (func_get_args() as $args){
            if(empty($args)){
                throw new ESDBaccessException("Un ou plusieurs paramètres de connexion à la base de données sont manquants", self::MISSING_PARAMETER);
            }
        }

        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;

    }

    /**
     * @inheritDoc
     */
    public function connectToDB(): bool
    {

        try {
            // L'objet est déjà créé, pas besoin de le refaire
            if(!is_null($this->sql)){
                $this->disconnectToDB();
            }

            $this->sql = new mysqli($this->host, $this->user, $this->password, $this->database, $this->port);
            $this->sql->set_charset("utf8");

            // on va utiliser les deux flags pour obtenir les erreurs mysql et strict mode
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            return true;
        }
        catch (ESDBaccessException $e){
            throw new ESDBaccessException("Impossible de se connecter à la base de données " . $this->database, self::CONNECT_ERROR, $e);
        }


    }

    /**
     * @inheritDoc
     */
    public function disconnectToDB(): bool
    {
        try {
            // L'objet est déjà créé, pas besoin de le refaire
            if(is_null($this->sql)){
                return true;
            }

            // On ferme la connexion puis on vide la variable
            $this->sql->close();
            $this->sql = null;

            return true;
        }
        catch(ESDBaccessException $e){
            throw new ESDBaccessException("Impossible de se déconnecter de la base de données " . $this->database, self::DISCONNECT_ERROR, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function pingToDB(): bool
    {
        return $this->sql->ping();
    }

    /**
     * @inheritDoc
     */
    public function querySelect(string $table, $condition = null, array $columns = null): void
    {
        // Vérifier si les paramètres sont bien renseignés
        if (!isset($table)){
            throw new ESDBaccessException("Aucune table n'a été donnée paramètre", self::MISSING_TABLE_DATABASE);
        }

        // On nettoie les colonnes
        if ($columns == null){
            $columns = "*";
        }
        else {
            $columns = implode(', ', array_filter(array_map('trim', $columns))); // On supprime les espaces vides puis on sépare tout par une virgule
        }

        // On nettoie aussi la table
        $table = trim($table);

        // On prépare ensuite la requête et on ajoute la condition si elle existe
        $query = "SELECT " . htmlentities($columns) . " FROM " . htmlentities($table);

        // On précise que la variable results est un array pour y stocker tous les résultats + on le vide d'abord
        $this->results = [];

        // Si la condition est renseignée, on la rajoute
        if ($condition !== null) {
            $condition = trim(htmlentities($condition));

            $query .= $query . $condition;
        }

        $query = $query . ";"; // Sans oublier le ; qui pourrait être nécessaire

        // On peut donc executer la requete simple
        $execute = $this->sql->real_query($query);

        // Si la requête est ok
        if ($execute == true) {

            if ($this->sql->field_count) {
                $results = $this->sql->store_result();

                // tant qu'on a des résultats, on va tous les ajouter un par un à la suite dans un tableau
                while ($obj = $results->fetch_object()) {
                    array_push($this->results, $obj);
                }

                $this->numOfRows = $results->num_rows;

                $results->free();
            } else {
                $this->affectedRows = $this->sql->affected_rows;
            }
        } else {
            throw new ESDBaccessException("Une erreur a échoué pour la requête SQL suivante : " . $query, self::QUERY_SELECT_ERROR);
        }
    }

    /**
     * @inheritDoc
     */
    public function queryInsert(string $table, array $columns, array $values, $condition = null): void
    {
        // Vérifier si les paramètres sont bien renseignés
        if (empty($columns)){
            throw new ESDBaccessException("Aucune colonne n'a été donnée en paramètre", self::MISSING_COLUMNS_DATABASE);
        }
        if (empty($values)){
            throw new ESDBaccessException("Aucune valeur n'a été donnée en paramètre", self::MISSING_VALUES_DATABASE);
        }
        if (!isset($table)){
            throw new ESDBaccessException("Aucune table n'a été donnée paramètre", self::MISSING_TABLE_DATABASE);
        }

        // il faut également que le nombre de colonnes renseignées soit identique au nombre de valeurs
        if (sizeof($columns) !== sizeof($values)){
            throw new ESDBaccessException("Le nombre de colonnes doit être identique au nombre de valeurs", self::ERROR_ORDER_VALUES);
        }

        // On nettoie les variables avant
        $columns = implode(', ', array_filter(array_map('trim', $columns))); // On supprime les espaces vides puis on sépare tout par une virgule
        $values = implode(', ', array_filter(array_map('trim', $values))); // On supprime les espaces vides puis on sépare tout par une virgule
        $table = trim($table);

        // On prépare ensuite la requête et on ajoute la condition si elle existe
        $query = "INSERT INTO " . htmlentities($table) . " (" . htmlentities($columns) . ") VALUES (" . htmlentities($values) . ") ";

        // On précise que la variable results est un array pour y stocker tous les résultats + on le vide d'abord
        $this->results = [];

        // Si la condition est renseignée, on la rajoute
        if ($condition !== null) {
            $condition = trim(htmlentities($condition));

            $query .= $query . $condition;
        }

        $query = $query . ";"; // Sans oublier le ; qui pourrait être nécessaire

        // On peut donc executer la requete simple
        $execute = $this->sql->real_query($query);

        // Si la requête est ok
        if ($execute == true) {

            if ($this->sql->field_count) {
                $results = $this->sql->store_result();

                // tant qu'on a des résultats, on va tous les ajouter un par un à la suite dans un tableau
                while ($obj = $results->fetch_object()) {
                    array_push($this->results, $obj);
                }

                $this->numOfRows = $results->num_rows;

                $results->free();
            } else {
                $this->affectedRows = $this->sql->affected_rows;
            }
        } else {
            throw new ESDBaccessException("Une erreur a échoué pour la requête SQL suivante : " . $query, self::QUERY_SELECT_ERROR);
        }

    }


    /**
     * @inheritDoc
     */
    public function preparedQuerySelect(array $columns, string $table, string $condition, string $bind_type, array $bind_data): void
    {
        // Vérifier si les paramètres sont bien renseignés
        if (empty($columns)){
            throw new ESDBaccessException("Aucune colonne n'a été donnée en paramètre", self::MISSING_COLUMNS_DATABASE);
        }
        if (empty($values)){
            throw new ESDBaccessException("Aucune valeur n'a été donnée en paramètre", self::MISSING_VALUES_DATABASE);
        }
        if (!isset($table)){
            throw new ESDBaccessException("Aucune table n'a été donnée paramètre", self::MISSING_TABLE_DATABASE);
        }

        // On vérifie aussi que les types et les données ont le même nombre de données et ne sont pas vides

    }


    /**
     * @inheritDoc
     */
    public function preparedQueryInsert(array $columns, array $values, string $table, string $condition, string $bind_type, array $bind_data): void
    {
        // TODO: Implement preparedQueryInsert() method.
    }


    /**
     * @inheritDoc
     */
    public function allResults(): ?array
    {
        return $this->results;
    }

    /**
     * @inheritDoc
     */
    public function thisResult()
    {
        // On va récupérer tous les résultats pour ensuite chercher le premier qui est retourné
        $thisResult = current($this->results);

        next($thisResult);

        // Par contre, si le résultat n'est pas sous forùe d'objet, erreur
        if(!is_object($thisResult)){
            throw new ESDBaccessException("Erreur lors de la récupération. Le résultat n'est pas un array ou un object", self::NEXT_RESULT_NOT_OBJECT);
        }
    }

    /**
     * @inheritDoc
     */
    public function affectedRows(): int
    {
        return $this->affectedRows;
    }

    /**
     * @inheritDoc
     */
    public function numOfRows(): int
    {
        return $this->numOfRows;
    }

}
