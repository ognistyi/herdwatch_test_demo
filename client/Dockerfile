FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    bash \
    git \
    curl \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install

ENTRYPOINT ["tail", "-f", "/dev/null"]


