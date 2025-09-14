# Use official PHP-Apache image with PHP 8.0
FROM php:8.0-apache

# Install MySQL extensions for PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy all your PHP source code to Apache web root
COPY . /var/www/html/

# Expose port 80 to HTTP traffic
EXPOSE 80
