#!/bin/sh
dir="/var/www/discounts.local"
cd "$dir"
php bin/console composer:install
php bin/console cache:clear
php bin/console doctrine:schema:create
php bin/console doctrine:fixtures:load .... yes