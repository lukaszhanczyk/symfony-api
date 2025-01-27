FROM php:8.2-fpm

RUN apt-get update && apt-get install -y

RUN apt-get update && apt-get install -y --no-install-recommends \
		git \
		zlib1g-dev \
		libxml2-dev \
		libzip-dev \
		libpq-dev \
        cron \
        vim\
	&& docker-php-ext-install \
		zip \
		pdo  \
		pdo_pgsql \
		pdo_mysql \
		pgsql \
		intl

RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data

RUN curl -O https://getcomposer.org/download/2.2.5/composer.phar \
&&  mv composer.phar /usr/local/bin/composer \
&&  chmod +x /usr/local/bin/composer
WORKDIR /var/www/html/