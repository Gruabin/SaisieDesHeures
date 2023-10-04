# Introduction

Application servant de template pour tous les projets symfony de Gruau.

# Getting Started

Pour utiliser l'application en localhost vous avez juste à suivre les étapes suivantes:

1. Vérifier que vous avez php 8
2. Faite `composer install` pour installer toutes les dépendances
3. En cas de modification du fichier `AzureController.php` (sert en localhost) il faut également modifier le fichier `PipelineAzureController.php` (set sur app service)
4. Assurez vous d'avoir une base de donnée compatible à l'application
5. Une fois la base de donnée connectée à l'application faites les commandes suivantes:

    A. php bin/console make:migration

    B. php bin/console doctrine:migrations:migrate

6. Executez la commande suivante: `php -S 127.0.0.1:8000 -t public` ou `symfony serve` et ensuite `npm run watch`
7. Si un soucis de 'openSSL' voir # TIPS
8. Dans la section recherche de votre éditeur, rechercher "TEMPLATE" et remplacer par le nom de votre application

# Base donnée

Concernant la base de donnée il faut impérativement utiliser PostgresSQL

# Mise en prod

N'oublier pas de demander les accès à l'infra pour obtenir les informations suivantes ainsi que de les saisir comme variable d'environement

OAUTH_AZURE_CLIENT_ID=""

OAUTH_AZURE_CLIENT_SECRET=""

AZURE_TENANT_ID=""

# TIPS

Si vous êtes en localhost et que vous avez un problème de type 'openSSL' modifier le fichier 'CurlFactory' se situant: vendor\guzzlehttp\guzzle\src\Handler\CurlFactory.php
Si vous ne trouvez pas le fichier faites la commande:

`composer require symfony/http-client`

Allez à la ligne 358 et 359 et replacez:

                $conf[\CURLOPT_SSL_VERIFYHOST] = 2;

                $conf[\CURLOPT_SSL_VERIFYPEER] = true;

Par

                $conf[\CURLOPT_SSL_VERIFYHOST] = 0;

                $conf[\CURLOPT_SSL_VERIFYPEER] = false;

Et voilà vous n'avez plus ce problème !

# SSO

Pour activer le SSO il vous suffit juste de vous diriger vers le fichier `security.yaml` situer `config\packages\security.yaml`
Ensuite vous n'avez qu'à ajouter vos routes sécurisée
Si l'utilisateur est redirigé vers un endroit dont il n'a pas accès, il sera automatiquement renvoyé vers la page de connexion
