# Use an official PHP runtime as a parent image
FROM php:8.0-apache

# Set the working directory in the container
WORKDIR /var/www/html
# Install PDO MySQL extension and other PHP extensions
RUN docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install opcache
# Enable mod_rewrite
RUN a2enmod rewrite

# Copy the current directory contents into the container at /var/www/html
COPY . /var/www/html

# set environment variable
ENV MAC_ADDRESS none
ENV MECH_NO none
ENV DB_HOST none
ENV DB_USER none
ENV DB_PASSWORD none
ENV DB_NAME none
ENV DEBUG false

# Expose port 80 to the outside world
EXPOSE 80

# Command to run on container start
CMD ["apache2-foreground"]
