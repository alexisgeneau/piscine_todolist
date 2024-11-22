# Mini Plateforme de Gestion de Tâches

Cette application est une petite plateforme de gestion de tâches écrite en PHP, utilisant des objets JSON comme base de données et un mini router simplifié.

## Fonctionnalités

- **Ajouter une tâche** : Ajoutez une tâche avec un nom et, éventuellement, une date limite.
- **Lister les tâches** : Affiche toutes les tâches avec leur statut (terminée ou non).
- **Marquer une tâche comme terminée** : Change le statut d'une tâche.
- **Supprimer une tâche** : Supprime une tâche de la liste.
- **Routing simplifié** : Gère les différentes actions via un mini router.

## Architecture

Le projet est structuré comme suit :
```
/project
├── /public
│   ├── index.php          # Point d'entrée principal
│   ├── router.php         # Mini router pour dispatcher les actions
├── /data
│   ├── tasks.json         # Base de données JSON des tâches
├── /src
│   ├── Task.php           # Classe pour gérer les tâches
│   ├── TaskManager.php    # Gestion des opérations CRUD sur les tâches
│   ├── Controller.php     # Contrôleur pour le traitement des actions
├── README.md              # Documentation du projet
```

## Utilisation

Ajout d'une tâche
Rendez-vous sur la page d'accueil.
Remplissez le champ de texte pour ajouter une tâche.
Cliquez sur "Ajouter".
Marquer une tâche comme terminée
Cliquez sur le bouton "Marquer comme terminée" à côté d'une tâche.
Supprimer une tâche
Cliquez sur le bouton "Supprimer" à côté d'une tâche.
Structure des tâches (JSON)
Les tâches sont stockées sous la forme d'objets JSON dans le fichier tasks.json. Chaque tâche a les propriétés suivantes :

```
{
"id": 1,
"name": "Acheter du pain",
"is_completed": false,
"due_date": "2024-12-01"
}
```

id : Identifiant unique de la tâche.
name : Nom de la tâche.
is_completed : Statut de la tâche (true ou false).
due_date : Date limite (optionnelle).

Étapes pour ajouter une fonctionnalité
Créer une méthode dans TaskManager : Implémentez une nouvelle méthode (ex : markAsCompleted($id)).
Appeler la méthode dans le contrôleur : Ajoutez le traitement correspondant dans le contrôleur.
Ajouter une route dans router.php : Associez une nouvelle route pour accéder à l'action (ex : /task/complete).
Exemple de mini-router

Le fichier router.php contient une logique simple pour gérer les routes :

```php
switch ($_SERVER['REQUEST_URI']) {
case '/':
require 'index.php';
break;
case '/task/add':
require '../src/Controller.php';
break;
default:
http_response_code(404);
echo "Page non trouvée.";
}
```

Améliorations possibles
Ajouter une barre de recherche pour filtrer les tâches.
Implémenter un système de pagination si le fichier JSON devient volumineux.
Ajouter une validation plus robuste pour les entrées utilisateur.
