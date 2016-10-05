
FROM lastcallmedia/php:7.0

ADD . /srv

RUN cd /srv && composer install --optimize-autoloader \
  && chown www-data:www-data /srv/cache \
  && rmdir /var/www/html \
  && ln -s /srv/public/ /var/www/html