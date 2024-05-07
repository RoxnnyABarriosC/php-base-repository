FROM php:8.1-apache as deps

WORKDIR /var/www/html

RUN chmod -R 777 /var/www/html

# Enable Apache modules
RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    libssl-dev \
    autoconf \
    gcc \
    make

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN pecl install mongodb && docker-php-ext-enable mongodb
RUN pecl config-set php_ini /etc/php.ini
RUN echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini

# Increase PHP memory limit
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/memory-limit.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set ServerName directive globally to suppress warning message
RUN echo "ServerName localhost" | tee /etc/apache2/conf-available/fqdn.conf && a2enconf fqdn

# Copy application files
COPY . .

EXPOSE 80

FROM deps as dev

ENTRYPOINT ["apache2-foreground"]