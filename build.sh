#!/bin/sh
composer install
php bin/console cache:clear
php bin/console doctrine:schema:create
echo "y" | php bin/console doctrine:fixtures:load