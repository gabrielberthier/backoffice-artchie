FROM php:8.0-fpm

COPY scripts/wait-for-it.sh /usr/bin/wait-for-it

RUN chmod +x /usr/bin/wait-for-it

# Install system dependencies
RUN apt-get update && apt-get install -y git

RUN apt-get update && \
  apt-get install -y --no-install-recommends libssl-dev zlib1g-dev curl git unzip netcat libxml2-dev libpq-dev libzip-dev && \
  pecl install apcu && \
  docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
  docker-php-ext-install -j$(nproc) zip opcache intl pdo_pgsql pgsql && \
  docker-php-ext-enable apcu pdo_pgsql sodium && \
  apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Common
RUN apt-get update \
  && apt-get install -y \
  openssl \
  gnupg2

# strings
RUN apt-get update \
  && apt-get install -y libonig-dev \
  && docker-php-ext-install -j$(nproc) \
  gettext \
  mbstring


# ssh2
RUN apt-get update \
  && apt-get install -y \
  libssh2-1-dev

# memcached
RUN apt-get update \
  && apt-get install -y \
  libmemcached-dev \
  libmemcached11

COPY --from=composer /usr/bin/composer /usr/bin/composer


# Install PHP Code sniffer
RUN curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar \
  && chmod 755 phpcs.phar \
  && mv phpcs.phar /usr/local/bin/ \
  && ln -s /usr/local/bin/phpcs.phar /usr/local/bin/phpcs \
  && curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar \
  && chmod 755 phpcbf.phar \
  && mv phpcbf.phar /usr/local/bin/ \
  && ln -s /usr/local/bin/phpcbf.phar /usr/local/bin/phpcbf

# Install PHPUnit
RUN curl -OL https://phar.phpunit.de/phpunit.phar \
  && chmod 755 phpunit.phar \
  && mv phpunit.phar /usr/local/bin/ \
  && ln -s /usr/local/bin/phpunit.phar /usr/local/bin/phpunit

RUN ln -s /usr/bin/php8 /usr/bin/php

# COPY php/php.ini /usr/local/etc/php/
# COPY php/php.ini /etc/php8/conf.d/custom.ini

# Set working directory
WORKDIR /var/www

RUN chown -R root:www-data /var/www
RUN chmod u+rwx,g+rx,o+rx /var/www
RUN find /var/www -type d -exec chmod u+rwx,g+rx,o+rx {} +
RUN find /var/www -type f -exec chmod u+rw,g+rw,o+r {} +


CMD composer i -o ; vendor/bin/doctrine-migrations migrate ;  php-fpm 


EXPOSE 9000