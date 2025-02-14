# Use an official PHP image with Apache
FROM php:8.1-apache

# Install required PHP extensions, including php-zip
RUN apt-get update && apt-get install -y \
    unzip \
    php-zip \
    && docker-php-ext-install mysqli pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files (make sure composer.json exists)
COPY . .

# Run Composer install (only if composer.json exists)
RUN if [ -f "composer.json" ]; then composer install --no-dev --optimize-autoloader; fi

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache when the container runs
CMD ["apache2-foreground"]
