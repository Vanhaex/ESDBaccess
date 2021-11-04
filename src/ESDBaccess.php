<?php

namespace ESDBaccess;

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
    public function connectionInformation(string $host, int $port, string $user, string $password, string $database): void
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
            $this->sql = NULL;

            return true;
        }
        catch(ESDBaccessException $e){
            throw new ESDBaccessException("Impossible de se déconnecter de la base de données " . $this->database, self::DISCONNECT_ERROR, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function querySelect(array $columns, string $table, string $condition): void
    {
        // Vérifier si les paramètres sont bien renseignés
        if (empty($columns)){
            throw new ESDBaccessException("Aucune colonne n'a été donnée en paramètre", self::MISSING_COLUMNS_DATABASE);
        }
        if (!isset($table)){
            throw new ESDBaccessException("Aucune table n'a été donnée paramètre", self::MISSING_TABLE_DATABASE);
        }

        // On nettoie les variables avant
        $columns = implode(', ', array_filter(array_map('trim', $columns))); // On supprime les espaces vides puis on sépare tout par une virgule
        $table = trim($table);
        $condition = trim($condition);

        // On prépare ensuite la requête et on ajoute la condition si elle existe
        $query = "SELECT " . $columns . " FROM " . $table;
        if ($condition !== ""){
            $query .= $query . $condition;
        }
        
        $query .= $query . ";";
        
        // On peut donc executer la requete simple
        $execute = $this->sql->real_query($query);
        
        if ($execute == true){
            // Si la requete réussie et retourne un jeu de résultat on fait quelque chose
            if ($this->sql->field_count){
                $results = $this->sql->store_result();
                
                while ($obj = $results->fetch_object()){
                    array_push($this->results, $obj);
                }
                
                $this->runOfRows = $this->sql->num_rows;

                $this->results->free();
            }
            else {
                $this->affectedRows = $this->sql->affected_rows;
            }
        }
        else {
            throw new ESDBaccessException("Une erreur a échoué pour la requête SQL suivante : " . $query, self::QUERY_SELECT_ERROR);
        }
    }

    /**
     * @inheritDoc
     */
    public function queryInsert(array $columns, array $values, string $table, string $condition): void
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

        // On nettoie les variables avant
        $columns = implode(', ', array_filter(array_map('trim', $columns))); // On supprime les espaces vides puis on sépare tout par une virgule
        $values = implode(', ', array_filter(array_map('trim', $values))); // On supprime les espaces vides puis on sépare tout par une virgule
        $table = trim($table);
        $condition = trim($condition);
        
        // On prépare ensuite la requête et on ajoute la condition si elle existe
        $query = "INSERT INTO (" . $columns . ") VALUES (" . $values . ") " . $table;
        if ($condition !== ""){
            $query .= $query . $condition;
        }
        
        $query .= $query . ";";
        
        // On peut donc executer la requete simple
        $execute = $this->sql->real_query($query);
        
        if ($execute == true){
            // Si la requete réussie et retourne un jeu de résultat on fait quelque chose
            if ($this->sql->field_count){
                $results = $this->sql->store_result();
                
                while ($obj = $results->fetch_object()){
                    array_push($this->results, $obj);
                }
                
                $this->runOfRows = $this->sql->num_rows;

                $this->results->free();
            }
            else {
                $this->affectedRows = $this->sql->affected_rows;
            }
        }
        else {
            throw new ESDBaccessException("Une erreur a échoué pour la requête SQL suivante : " . $query, self::QUERY_SELECT_ERROR);
        }

    }


    /**
     * @inheritDoc
     */
    public function preparedQuerySelect(array $columns, string $table, string $condition, string $bind_type, array $bind_data): void
    {
        // TODO: Implement preparedQuerySelect() method.
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
    public function allResults(): array
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
