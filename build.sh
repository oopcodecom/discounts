#!/bin/sh
composer install --prefer-dist
php bin/console doctrine:schema:create
echo "y" | php bin/console doctrine:fixtures:load
php bin/console cache:clear