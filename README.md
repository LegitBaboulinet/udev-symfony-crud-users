# udev--symfony-crud-users

## Procédure d'installation

**Récupération du projet**

Pour récupérer le projet, clonez depuis le dépôt Git :
``` bash
git clone https://github.com/LegitBaboulinet/udev-symfony-crud-users.git
```
Ou téléchargez le directement depuis github.

**Installation des dépendances**

Ouvrez ensuite une invite de commande dans le dossier téléchargé et tapez la commande suivante.

``` bash
composer install
```
Cette commande va installer les dépendances requises pour le bon fonctionnement de l'application.

**Configuration de la base de données**

La BDD (base de données pour ceux qui ne savent pas ce que ça veut dire) utilisée pour le projet est configurée pour MySQL.
Mais, il faut changer les fichiers de configuration pour adapter la configuration de la connexion.

Pour cela, ouvrez le fichier '.env'. Situé à la racine du projet.
Changez la ligne 27 :
```text
DATABASE_URL=pgsql://postgres@127.0.0.1:5432/symfony_app
```
Remplacez la comme cela pour une configuration avec **MySQL** :

```text
DATABASE_URL=mysql://root@127.0.0.1:3306/LE_NOM_DE_VOTRE_BDD
# Ici root est l'utilisateur admin.
# Avec WAMP, L'utilisateur root n'a pas de MDP par défaut.
# Si votre utilisateur a un MDP, alors remplacez la ligne par celle-ci :
DATABASE_URL=mysql://VOTRE_UTILISATEUR:VOTRE_MDP@127.0.0.1:3306/LE_NOM_DE_VOTRE_BDD
```

**ATTENTION**

Il faut également changer le fichier doctrine.yaml situé dans le dossier config/packages.
Il définit la configuration de la base de données.  
Remplacez le par le fichier MySQL utilisé dans le projet précédent.

**Migration de la base de données**

La dernière étape consiste à créer les tables en base grâce aux classes persistentes.
Pour cela, tapez les deux commandes suivantes :

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```