# Use a PHP 8.1 image
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql

# Other setup commands


# Set the working directory
WORKDIR /var/www/html

# Copy your PHP application files into the container
COPY . /var/www/html

# Install Composer (if you need it)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies using Composer
RUN composer install

# Expose port 9000 for the application
EXPOSE 9000

# Start the PHP-FPM server
CMD ["php-fpm"]
