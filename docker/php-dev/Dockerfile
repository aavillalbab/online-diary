FROM dsarhoya/supervisor-php-8.1-alpine:1

# Configuración de PHP
COPY docker/php-dev/dsy.ini $PHP_INI_DIR/conf.d/
COPY docker/php-dev/zdsy.conf /usr/local/etc/php-fpm.d/

# Supervisord
COPY docker/php-dev/dsy.conf /etc/supervisor/conf.d/dsy.conf

# Usuarios
RUN addgroup -g 1001 ubuntu && adduser -D -u 1001 -G ubuntu ubuntu

# Comando por defecto
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
