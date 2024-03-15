FROM php:8.2-fpm

ARG APP_DEBUG
ARG APP_ENV
ARG APP_SECRET
ARG AZURE_TENANT_ID
ARG CORS_ALLOW_ORIGIN
ARG COMPOSER_NO_DEV
ARG DATABASE_HOST
ARG DATABASE_NAME
ARG DATABASE_PASSWORD
ARG DATABASE_USER
ARG MAILER_DSN
ARG PHP_INI_ENV
ARG OAUTH_AZURE_CLIENT_ID
ARG OAUTH_AZURE_CLIENT_SECRET

ENV APP_ENV=$APP_ENV
ENV APP_DEBUG=$APP_DEBUG
ENV APP_SECRET=$APP_SECRET
ENV CORS_ALLOW_ORIGIN=$CORS_ALLOW_ORIGIN
ENV AZURE_TENANT_ID=$AZURE_TENANT_ID
ENV COMPOSER_NO_DEV=$COMPOSER_NO_DEV
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV DATABASE_HOST=$DATABASE_HOST
ENV DATABASE_NAME=$DATABASE_NAME
ENV DATABASE_USER=$DATABASE_USER
ENV DATABASE_PASSWORD=$DATABASE_PASSWORD
ENV MAILER_DSN=$MAILER_DSN
ENV OAUTH_AZURE_CLIENT_ID = $OAUTH_AZURE_CLIENT_ID
ENV OAUTH_AZURE_CLIENT_SECRET = $OAUTH_AZURE_CLIENT_SECRET

# Installation des packages
RUN apt-get update \
    && apt-get install -y --no-install-recommends locales apt-utils git g++ libicu-dev libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev unzip

# Configuration de locale
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen

# Config PHP
RUN mv "$PHP_INI_DIR/php.ini-$PHP_INI_ENV" "$PHP_INI_DIR/php.ini"
COPY --link ./docker/php/app.ini $PHP_INI_DIR/conf.d/
COPY --link ./docker/php/app.$APP_ENV.ini $PHP_INI_DIR/conf.d/

# Installation des extensions PHP
RUN docker-php-ext-configure intl \
    && docker-php-ext-install calendar dom gd intl mbstring pdo pdo_mysql opcache xsl zip
    #&& pecl install apcu  \
    #&& docker-php-ext-enable apcu

# Timezone Paris
RUN ln -fs /usr/share/zoneinfo/Europe/Paris /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata

# Configurez le répertoire de travail
WORKDIR /var/www/html

# Installez App
COPY --link ./app /var/www/html

# Installez Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install && composer dump-autoload --optimize
RUN chown -R www-data:www-data /var/www/html && chmod -R +x /var/www/html/bin/.

# Démarrez le serveur PHP-FPM
CMD ["php-fpm"]
