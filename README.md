Gruau - Saisi des heures
--------
Saisi des heures est une application web légère conçue pour simplifier la gestion de temps 
et la saisie des heures pour les employés. Elle permet aux utilisateurs de suivre, 
d'enregistrer et de gérer efficacement les heures travaillées sur différents projets. 

[![PHP](https://img.shields.io/badge/PHP-8.0.2-brightgreen.svg?logo=php&logoColor=white)](https://www.php.net/)
[![Nginx](https://img.shields.io/badge/Nginx-latest-brightgreen.svg?logo=nginx&logoColor=white)](https://www.nginx.com/)
[![Symfony](https://img.shields.io/badge/Symfony-6.*-brightgreen.svg?logo=symfony&logoColor=white)](https://www.symfony.com/)

# Sommaire
<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->

- [Installation](#installation)
- [TIPS](#tips)
- [SSO](#sso)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->


# Installation

Pour utiliser l'application en localhost vous avez juste à suivre les étapes suivantes

1. Vérifier que vous avez php 8
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
6. Démarrer le service webpack
   ```shell
   npm run watch
   ```
7. Vous pouvez maintenant accéder à l'application via l'url `http://localhost:8000`

# TIPS

Si vous êtes en localhost et que vous avez un problème de type 'openSSL' modifier le fichier 'CurlFactory' se situant: vendor\guzzlehttp\guzzle\src\Handler\CurlFactory.php
Si vous ne trouvez pas le fichier faites la commande:
```shell
composer require symfony/http-client
```

Chercher et replacez:

```
$conf[\CURLOPT_SSL_VERIFYHOST] = 2;
$conf[\CURLOPT_SSL_VERIFYPEER] = true;
```

Par

```
$conf[\CURLOPT_SSL_VERIFYHOST] = 0;
$conf[\CURLOPT_SSL_VERIFYPEER] = false;
```

Et voilà vous n'avez plus ce problème !

# SSO

Pour activer le SSO il vous suffit juste de vous diriger vers le fichier `security.yaml` situer `config\packages\security.yaml`
Ensuite vous n'avez qu'à ajouter vos routes sécurisée
Si l'utilisateur est redirigé vers un endroit dont il n'a pas accès, il sera automatiquement renvoyé vers la page de connexion
