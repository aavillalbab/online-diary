#!/bin/sh

bin/console cache:clear
bin/console assets:install

setfacl -R -m u:33:rwX -m u:1000:rwX /app/var
setfacl -dR -m u:33:rwX -m u:1000:rwX /app/var

echo 'Ejecutando php-fpm'

php-fpm -F