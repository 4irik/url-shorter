# build application runtime, image page: <https://hub.docker.com/_/php>
FROM php:8.1.2-alpine as runtime

# install composer, image page: <https://hub.docker.com/_/composer>
COPY --from=composer:2.2.5 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_HOME="/tmp/composer"

# use directory with application sources by default
WORKDIR /app

# "fix" composer issue "Cannot create cache directory /tmp/composer/cache/..." for docker-compose usage
RUN set -x \
    mkdir ${COMPOSER_HOME}/cache \
    chmod -R 777 ${COMPOSER_HOME}/cache

# unset default image entrypoint
ENTRYPOINT []