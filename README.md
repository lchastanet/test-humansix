# Test Humansix

Prérequis :

- PHP 8.1

- Node 17.4

- Yarn 1.22

- Composer 2.2

- Mysql / MariaDB / Postgres

Cloner ce repository.

Installer les dépendances de back :

```
$ composer install
```

Installer les dépendances du front :

```
$ npm install
```

Créer un fichier .env.local à la racine du projet et renseigner au moins la clé DATABASE_URL sur le modèle du .env déjà présent avec votre configuration.

Créer la base de donnée :

```
$ php bin/console doctrine:database:create
```

Mettre à jour le schéma de la base de donnée :

```
$ php bin/console doctrine:migrations:migrate
```

Charger le jeu de données initiale depuis le fichier XML :

```
$ php bin/console doctrine:fixtures:load
```

 Générer les clés pour le JWT :

```
$ php bin/console lexik:jwt:generate-keypair
```

Lancer le serveur de développement du back :

```
$ php -S localhost:8000 -t public
```

A la place de la commande précédente si vous avez l'outils installé sur votre machine :

```
$ symfony serve --no-tls
```

Lancer le serveur de développement du front :

```
yarn encore dev-server --hot
```

L'appli est utilisable à l'adresse http://localhost:8000
