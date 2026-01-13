FROM php:8.5-apache

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Instalar Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Instalar herramientas útiles
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Copiar configuración personalizada de Apache y php
COPY Docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY Docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Habilitar Apache headers y rewrite (asegurar que está habilitado)
RUN a2enmod headers

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html
