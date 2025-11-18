# Use the official PHP image with Apache as a base
FROM php:apache

# Install the mysqli extension
RUN docker-php-ext-install mysqli
