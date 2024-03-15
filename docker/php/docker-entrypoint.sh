#!/bin/bash

# Navigate to the application directory
cd /var/www/html

# Install Composer dependencies
composer install

# Start PHP's built-in server
php -S 0.0.0.0:9000