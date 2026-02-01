# kop

TODO

## Lien direct

- [kop](#-appname-)
  - [Lien direct](#lien-direct)
  - [Développeurs](#développeurs)
  - [Environnement](#environnement)
    - [🏘️ Production](#️-production)
    - [🏠 Staging](#-staging)
    - [🏚️ Demo](#️-demohttps-appname-novademonet)
  - [Architecture](#architecture)
    - [Backend](#backend)
    - [Frontend](#frontend)
  - [Makefile](#makefile)
  - [Setup du projet](#setup-du-projet)
    - [Back-end](#back-end)
      - [Import des données](#import-des-données)
      - [Certificat SSL](#certificat-ssl)
    - [Frontend](#frontend-1)
      - [Pré-requis](#pré-requis)
      - [Installation](#installation)
  - [Storybook](#storybook)
  - [Tests](#tests)
  - [Déploiement](#déploiement)
    - [Review app](#review-app)
    - [Démo](#démo)

## Développeurs

- Site : **TODO**
- Backend : **TODO**

## Environnement

### 🏘️ [Production](https://novaway.fr)

### 🏠 [Staging](https://novaway.fr)

### 🏚️ [Demo](https://kop.novademo.net/)

Un [détail et un historique de chaque environnement](https://gitlab.novaway.net/novaproject/kop/-/environments) est disponible sur Gitlab (`Operations->Environment`).

## Architecture


### Backend


L'API repose sur ApiPlatform. les données sont rendues en REST.

#### DDD

L'architecture repose, comme tous les projets Novaway, sur une architecture DDD. 
Ici, le choix a été fait de structurer en feature pour ajouter plus facilement les nouveaux développements au fil des sprints.

#### CQRS

Nous avons choisi d'appliquer le pattern CQRS afin de sécuriser d'avantage les nombreuses opérations que possède le projet et permettre une meilleure maintenabilité et scalabilité par la suite.

### Stripe

#### Local

Créer une clé locale Stripe 

`` run stripe-cli listen --forward-to nginx:80/api/payment/confirm``
(Cliquer sur le lien dans la console pour valider la création de la clé locale)

Lancer le listener 

``docker-compose run stripe-cli listen --skip-verify --forward-to nginx:80/api/payment/confirm --api-key {limited_key}``

Mock d'un webhook

``docker-compose run stripe-cli trigger checkout.session.completed --api-key {limited_key}``

### Frontend

Le front est sur un projet séparé excepté la partie BackOffice (Easy Admin) et les mails.

#### BackOffice

L'url du Back Office est la suivante:

`/admin`

## Fonctionnement

### Workflow championnat


#### Lancement du championnat

Pour chaque Championnat qui a atteint le maximum de joueurs:
* Mise à jour du statut du championnat pour passer aux enchères

Statut: CREATED (1) -> BID_IN_PROGRESS (2)

Cron `app:championship:start`

#### Assignation des résultats d'enchère

Pour chaque Championnat après la fin du tour d'enchère:
* Assignation des pilotes/écuries aux joueurs ayant la plus haute enchère (en cas d'égalité, le joueur ayant enchéri le premier gagne)

Statut championnat: BID_IN_PROGRESS (2) -> BID_RESULT_PROCESSED (3)

Cron `app:championship:assign-item`

#### Assignation automatiques aux joueurs AFK ou sans budget suffisant

Pour chaque Championnat après assignation des résultats aux gagnants:
* Assignation automatiques de pilotes et écuries aux joueurs n'ayant plus le budget suffisant pour faire une enchères
* Assignation automatiques de pilotes et écuries aux joueurs AFK (joueur n'ayant pas fait d'enchères sur les 2 derniers tours)
* Si tous les joueurs ont 2 pilotes et 1 écurie assigné, le championnat passe à l'étape d'assignation des courses
* Sinon, les tours d'enchère est incrémenté et on revient au statut d'enchère

Statut championnat:
BID_RESULT_PROCESSED (3) -> NEED_TO_ASSIGN_RACES (4)
BID_RESULT_PROCESSED (3) -> BID_IN_PROGRESS (2)

Cron: `app:championship:assign-auto`

#### Assignation des courses

Pour chaque Championnat après la fin des enchères:
* Assignation des prochaines courses après la date actuelle + 7 jours (pour que les joueurs aient le temps d'effectuer leur strategie)
* Si il reste moins de 4 courses, le championnat est annulé

Statut championnat:
NEED_TO_ASSIGN_RACES (4) -> ACTIVE (5)

Statut première course championnat:
CREATED (1) -> ACTIVE (2)

Cron: `app:championship:assign-races`

#### Fin des strategies

Pour chaque Championnat actif et ayant une course active avec une date de fin de strategie dépassée:
* Soustrait une utilisation au compteur d'utilisation de pilote pour les stategies (si pas de pilote sélectionné, on soustrait en priorité sur le compteur du pilote 1)
* Affecte aléatoirement un pilote pour le duel si le joueur n'en a pas sélectionné.
* Soustrait une utilisation au compteur d'utilisation de pilote pour les duels
* Change le statut de la course du championnat à 'En attente de résultat'

Cron: `app:championship:end-strategy`

Statut course championnat:
ACTIVE (2) -> WAITING_RESULT (3)

#### Import des résultats de course

Action Back-Office.

L'import des résultats s'effectue pour 1 course sur une saison donnée.
Le fichier à importer est un CSV contenant la position du pilote pour chaque tour. Le format est le suivant:

| Pilotes        | Qualification | Sprint | 0 | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | {numéro tour} |
|----------------|---------------|--------|---|---|---|---|---|---|---|---|---|---|---------------|
| Max VERSTAPPEN | 1             | 1      | 1 | 1 | 1 | 2 | 1 | 2 | 1 | 2 | 1 | 2 | 1             |

_Note: La position sprint peut être vide si la course n'a pas d'epreuve sprint_

Cet import permet d'enregistrer les résultats par tour mais aussi les performances générales des pilotes et écuries.

Enfin l'import met a jour le statut de la course du championnat à 'Résultat effectué'

Statut course championnat:
WAITING_RESULT (3) -> RESULT_PROCESSED (4)

#### Génération des performances sur les championnats

Action Back-Office.

Une fois l'import de résultat effectué, il faut générer les performances pour les championnats.
Cette opération sert à calculer les performances avec les bonus utilisés,
de calculer les scores/points/positions des joueurs sur les différents championnats.

_**Attention: Une fois les performances générées il n'est actuellement plus possible de supprimer les résultats importés car nous ne traçons pas les "mouvements" de score/points/positions des joueurs.
Il est donc nécessaire de s'assurer de la validité des résultats importés avant de générer les performances.**_


## Makefile

Un fichier Makefile centralise l'ensemble des commandes fréquentes pour le développement de l'outil.

La commande `make` permet de lister les commandes make disponibles.

## Setup du projet

Lancement des containers Docker

`make docker-up`

#### Installer l'encryption de JWT
```sh
 phpd bin/console lexik:jwt:generate-keypair
```

### Back-end

#### Import des données

```sh
 phpd bin/console do:mi:mi
 make setup-database
```

#### Données de test
```sh
 phpd bin/console do:fix:load
```

### Comptes utilisateur

#### Administrateurs

| Rôle                 | Commentaire            | Email                     | Mot de passe |
|----------------------|------------------------|---------------------------|--------------|
| Super Administrateur | Tous les droits        | admin+super@novaway.fr    | password     |


#### Certificat SSL (**indispensable pour le lancement de NGINX**)

Il est possible de générer rapidement un certificat SSL qui pointera sur l'IP local de la machine (192.168.x.x)

Il faut en premier installer `mkcert` :

```sh
make install-mkcert
```

Puis générer le certificat :

```sh
make regenerate-mkcert
```
**En cas d'erreur** : `ERROR: failed to save certificate key: open config/docker/images/nginx/localhost.key: is a directory`   
Supprimer les dossiers `config/docker/images/nginx/localhost.crt` et `config/docker/images/nginx/localhost.key` puis relancer la commande ou :
```sh
rm -rf config/images/nginx/localhost.*
make regenerate-mkcert
```

**Partager le certificat sur Android**

Pour avoir accès au domaine en SSL, il faut installer le certificat localement sur le téléphone.

Les étapes sur Android 10 :

- copier le contenu du fichier `config/docker/images/nginx/rootCA.pem` sur le téléphone
- ensuite, se rendre dans les paramètres, puis **sécurité** -> **autres paramètres** -> **cryptage et références** et **installer depuis le stockage** (le chemin peu varier suivant les versions d'Android)
- sélectionner le fichier **rootCA.pem** dans le téléphone et lui donner un nom
- redémarrer le navigateur s'il est déjà ouvert

L'accès au domaine https://192.168.x.x devrait fonctionner

### Frontend

#### Pré-requis

Il est important d'utiliser la version de NodeJS à l'aide de l'outil `nvm` :

```sh
nvm use // puis nvm install si besoin est
```

Dans le cas contraire, toutes les commandes ci-dessous sont disponible dans le fichier `Makefile` en utilisant un container Docker.

#### Installation

Installation des dépendences :

```sh
npm install
--
make npm-install
```

Lancement d'un serveur de développement à l'aide de Webpack. Webpack va démarrer un proxy de `127.0.0.1` sur le port 3000 et intégrer le [Hot Module Replacement](https://webpack.js.org/guides/hot-module-replacement/) des assets :

```sh
npm start
--
make npm-start
```

Compilation des assets en mode production

```sh
npm run build
--
make npm-build
```

## Storybook

Le projet possède une story de chaque vue à l'aide de l'outil [Storybook](https://storybook.js.org/).

Lancer le storybook

```sh
npm run storybook
```

## Développement

Commit à la norme conventionnal commit : [documentation](doc/CONVENTIONAL COMMITS.md)

## Tests

Lancement des tests fonctionnels Cypress via Docker

```sh
make cypress-run
```

Utilisation de l'UI Cypress (avec le dossier bin préalablement téléchargé et copier dans le dossier _cypress_ à la racine du projet)

```sh
npm run cypress:open
```

## Déploiement

### Semantic release
- [documentation](doc/SEMANTIC RELEASE.md)

### Review app

Il est possible de générer une review app (un environnement créé à partir de la branche Git en cours). Le nom de la branche doit être obligatoirement préfixée de `review/` (par exemple `review/login-api`).

L'URL de l'environnement sera affiché depuis la merge request de la branche Git.

### Démo

Chaque modification sur la branche `develop` déclenche une pipeline de déploiement sur l'[environnement de démo](https://api-kop.novademo.net/).
