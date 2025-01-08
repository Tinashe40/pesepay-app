FROM php:8.1-cli

# Install required libraries
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql \
    && rm -rf /var/lib/apt/lists/*



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
