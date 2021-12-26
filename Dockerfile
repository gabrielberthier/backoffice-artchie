FROM php:8.0.0rc1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y git

# Common
RUN apt-get update \
  && apt-get install -y \
  openssl \
  git \
  gnupg2

# PHP
# intl
RUN apt-get update \
  && apt-get install -y libicu-dev \
  && docker-php-ext-configure intl \
  && docker-php-ext-install -j$(nproc) intl

# xml
RUN apt-get update \
  && apt-get install -y \
  libxml2-dev \
  libxslt-dev \
  && docker-php-ext-install -j$(nproc) \
  dom \
  xsl

# database
RUN docker-php-ext-install -j$(nproc) \
  mysqli \
  pdo \
  pdo_mysql 

RUN apt-get update

# Install Postgre PDO
RUN apt-get install -y libpq-dev \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install -j$(nproc) pdo_pgsql pgsql 

# strings
RUN apt-get update \
  && apt-get install -y libonig-dev \
  && docker-php-ext-install -j$(nproc) \
  gettext \
  mbstring

# compression
RUN apt-get update \
  && apt-get install -y \
  libbz2-dev \
  zlib1g-dev \
  libzip-dev \
  && docker-php-ext-install -j$(nproc) \
  zip \
  bz2

# ssh2
RUN apt-get update \
  && apt-get install -y \
  libssh2-1-dev

# memcached
RUN apt-get update \
  && apt-get install -y \
  libmemcached-dev \
  libmemcached11


# Install composer and put binary into $PATH
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/ \
  && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer

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


CMD ["php-fpm"]

EXPOSE 9000