FROM php:8.2-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    nginx \
    && docker-php-ext-install pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install --no-interaction --optimize-autoloader


COPY nginx.conf /etc/nginx/nginx.conf


EXPOSE 8000

#COPY docker-entrypoint.sh /usr/local/bin/
#RUN chmod +x /usr/local/bin/docker-entrypoint.sh
#ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]


CMD service nginx start && php-fpm
