Gruau - Saisie des heures
--------
Saisie des heures est une application web légère conçue pour simplifier la gestion de temps
et la saisie des heures pour les employés. Elle permet aux utilisateurs de suivre,
d'enregistrer et de gérer efficacement les heures travaillées sur différents projets.

[![PHP](https://img.shields.io/badge/PHP-8.3-brightgreen.svg?logo=php&logoColor=white)](https://www.php.net/)
[![Nginx](https://img.shields.io/badge/Nginx-latest-brightgreen.svg?logo=nginx&logoColor=white)](https://www.nginx.com/)
[![Symfony](https://img.shields.io/badge/Symfony-6.4.6-brightgreen.svg?logo=symfony&logoColor=white)](https://www.symfony.com/)

# Sommaire
<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->

- [Installation](#installation)
- [TIPS](#tips)
- [SSO](#sso)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->


# Installation

Pour utiliser l'application en localhost vous avez juste à suivre les étapes suivantes

1. Vérifier que vous avez php 8.3
2. Installer les dépendances

   ```shell
   composer install
   npm install
   ```

3. En cas de modification du fichier `AzureController.php` (sert en localhost) il faut également modifier le fichier `PipelineAzureController.php` (set sur app service)
4. Démarrer le service php sur windows

   ```shell
   php -S 127.0.0.1:8000 -t public
   ```

5. Démarrer le service webpack

   ```shell
   npm run watch
   ```

6. Vous pouvez maintenant accéder à l'application via l'url `http://localhost:8000`

# Linter
Aide pour executer les linters

```shell
vendor/bin/phpstan analyse src --memory-limit=2G
```

```shell
php vendor/bin/php-cs-fixer fix
```

```shell
php vendor/bin/rector process src
```

```shell
php vendor/bin/twig-cs-fixer lint --fix templates
```

```shell
php vendor/bin/php-cs-fixer fix
php vendor/bin/rector process src
php vendor/bin/twig-cs-fixer lint --fix templates
```

#Aide pour installer la bdd de test via docker

1er fois via :

```shell
./setup-test-environment.bat
```

Puis ensuite une fois installé seulement :

```shell
docker compose up
```

# Test Php
Executer les tests php

```shell
php bin/phpunit
```

# Linter + tests

```shell
php bin/phpunit
php vendor/bin/php-cs-fixer fix
php vendor/bin/rector process src
php vendor/bin/twig-cs-fixer lint --fix templates
```

# TIPS

Si vous êtes en localhost et que vous avez un problème de type 'openSSL' modifier le fichier 'CurlFactory' se situant: vendor\guzzlehttp\guzzle\src\Handler\CurlFactory.php
Si vous ne trouvez pas le fichier faites la commande:

```shell
composer require symfony/http-client
```

Chercher et remplacez:

```php
$conf[\CURLOPT_SSL_VERIFYHOST] = 2;
$conf[\CURLOPT_SSL_VERIFYPEER] = true;
```

Par

```php
$conf[\CURLOPT_SSL_VERIFYHOST] = 0;
$conf[\CURLOPT_SSL_VERIFYPEER] = false;
```

Et voilà vous n'avez plus ce problème !

# SSO

Pour activer le SSO il vous suffit juste de vous diriger vers le fichier `security.yaml` situé `config\packages\security.yaml`
Ensuite vous n'avez qu'à ajouter vos routes sécurisée
Si l'utilisateur est redirigé vers un endroit dont il n'a pas accès, il sera automatiquement renvoyé vers la page de connexion
