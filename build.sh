#!/bin/sh
docker-compose build
docker-compose up -d
docker exec -u dev discounts_php /bin/sh -c "composer install; bin/console c:c; bin/console d:s:d --full-database --force; bin/console d:s:c;echo 'y' | bin/console d:f:l"