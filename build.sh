#!/bin/sh
docker-comsoe build
docker-compose up -d
docker exec -u dev discounts_php /bin/sh -c "composer install;bin/console d:s:d --full-database --force; bin/console d:s:c;echo 'y' | bin/console d:f:l"