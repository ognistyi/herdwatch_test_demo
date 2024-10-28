#!/bin/bash
set -e


service nginx start


until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
  echo "Waiting for database connection..."
  sleep 2
done


php bin/console make:migration --no-interaction
php bin/console doctrine:migrations:migrate --no-interaction


php-fpm
