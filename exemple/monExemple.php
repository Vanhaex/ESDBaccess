<?php

// On oublie pas de l'importer !
use ESDBaccess;

// Je créé des variables contenant les paramètres de connexion car c'est plus propre
$host = "localhost";
$user = "user_database";
$password = "my_password";
$database = "my_database";
$port = 3306;

// j'instancie la classe en y ajoutant, comme paramètres, les variables créées précédemment
$database = new ESDBaccess\ESDBaccess($host, $user, $password, $database, $port);

// On essaie donc de se connecter
$database->connectToDB();

// Si l'on souhaite activer le mode "autocommit", c'est possible !
// false par défaut
$database->ESDBautocommit(true);

// Si on a pas d'erreur, on va donc arriver ici. On va réaliser une requête SELECT très simple
// qui va tout récupérer dans une table nommée "ma_table"
// Le premier paramètre qui est la table, le second pour les colonnes indiquée (null = *) et la
// troisième qui correpond à la condition que l'on souhaite ajouter
$database->querySelect("ma_table", null, null);

// Si on souhaite obtenir tous les résultats
$database->allResults();

// on si on en veut qu'un seul, comme le nom par exemple
// Attention : name corresond au nom de la colonne pour laquelle on souhaite voir le résultat
$database->thisResult()->name;

// On peut récupérer le nombre de résultats obtenus
$database->numOfRows();

// On a bien travaillé, on femre donc la connexion
$database->disconnectToDB();

?>




