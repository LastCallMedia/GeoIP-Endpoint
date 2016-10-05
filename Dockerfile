
FROM lastcallmedia/php:7.0

ADD . /srv

RUN cd /srv \
  && composer install --optimize-autoloader \
  && php src/console.php refreshdb \
  && chown www-data:www-data /srv/cache \
  && rmdir /var/www/html \
  && ln -s /srv/public/ /var/www/html