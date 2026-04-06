# README - Projet Laravel

## Prérequis

Avant de lancer le projet, assurez-vous d'avoir installé les outils suivants sur votre machine :

* Docker
* Composer
* Node.js et npm

## Script `dockerdo`

Le projet contient un script `dockerdo` qui sert de raccourci pour exécuter les commandes Laravel Sail à l’intérieur des conteneurs Docker.

```bash
#!/bin/bash

# Exécute la commande spécifiée avec Laravel Sail
./vendor/bin/sail "$@"
```

Toutes les commandes Laravel du projet doivent être exécutées via ce script.

Les étapes d’installation et de démarrage du projet sont décrites juste en dessous.

1. Copier le fichier d’environnement :

```bash
cp .env.example .env
```

2. Installer les dépendances PHP du projet :

```bash
composer i
```

3. Construire les conteneurs Docker :

```bash
./dockerdo build
```

4. Démarrer les conteneurs en arrière-plan :

```bash
./dockerdo up -d
```

5. Générer la clé de l’application Laravel :

```bash
./dockerdo artisan key:generate
```

6. Lancer les migrations de la base de données :

```bash
./dockerdo artisan migrate
```

7. Facultatif : insérer des données de démonstration dans la base de données :

```bash
./dockerdo artisan db:seed --class=InitDbSeeder
```

Cette commande permet de créer automatiquement plusieurs profils de test.

## Explication du code

Des codes ont été utilisés pour représenter les différents statuts des profils (`inactif`, `en attente`, `actif`) afin de faciliter l’évolution du projet.

Cette approche permet de manipuler des valeurs stables dans le code, par exemple :

* `INACTIVE`
* `WAITING`
* `ACTIVE`

Les labels affichés à l’utilisateur peuvent ensuite être modifiés ou traduits facilement sans impacter la logique métier.

Par exemple, `WAITING` peut être affiché comme « En attente » en français, puis comme « Waiting » en anglais.

Cette solution est particulièrement utile dans le cadre du test demandé, où les profils possèdent plusieurs statuts différents.

## Collection Postman

Une collection Postman est disponible pour tester les endpoints de l’API :

* [https://web.postman.co/workspace/Cyril-Buhlmann~5fea45cb-23ba-44ac-8bd1-661a7bf3fd48/collection/14349537-861c68d4-f29b-4800-a165-ac36a7678f91?action=share&source=copy-link&creator=14349537](https://web.postman.co/workspace/Cyril-Buhlmann~5fea45cb-23ba-44ac-8bd1-661a7bf3fd48/collection/14349537-861c68d4-f29b-4800-a165-ac36a7678f91?action=share&source=copy-link&creator=14349537)
