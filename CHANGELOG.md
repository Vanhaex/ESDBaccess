# CHANGELOG

## 16/02/22
**Version 1.0.2**
- BUGFIX : La fonction ESDBautocommit ne respectait pas ce qui était définit dans l'interface. La valeur par défaut "false" dans ESDBaccessInterface a été supprimée
- Remplacement de "self::" par "$this->" dans la classe ESDBaccess car utilisée par des classes non statiques

## 14/02/22
**Version 1.0.1**
- Ajout des méthodes "throw new" manquantes qui ne jetaient pas d'exceptions
- Création de la variable isActive pour initialiser la valeur de la fonction autocommit afin que ça soit plus propre
- Il manquait un return dans la méthode thisResult qui ne retournait rien
- Modification du README.md afin d'ajouter la commande pour installer avec Composer

## 10/11/21
**Version 1.0.0**
- Premier commit et version initiale du projet
- Ajout du tutoriel d'installation et d'utilisation dans le README
