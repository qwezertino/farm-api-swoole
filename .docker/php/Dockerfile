# Base image
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    librabbitmq-dev \
    supervisor \
    zip \
    unzip \
    git \
    curl \
    libbrotli-dev \
    libpng-dev \
    nodejs \
    npm \
    && docker-php-ext-install opcache gd pdo pdo_pgsql zip pcntl \
    && pecl install swoole redis \
    && docker-php-ext-enable swoole redis

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Chokidar for file watching
RUN npm install -g chokidar-cli

# Copy the supervisord config file
COPY ./.docker/php/php.ini /usr/local/etc/php/
COPY ./.docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Expose port
EXPOSE 8000

# Install Node.js dependencies (if you have a package.json)
COPY package.json /var/www/html/package.json
RUN npm install

#--chown=${WWWUSER}:${WWWUSER}
COPY --link ../../composer.json ../../composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-autoloader \
    --no-ansi \
    --no-scripts \
    --audit

# Start Octane server with supervisor
CMD ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisor/conf.d/supervisord.conf"]
