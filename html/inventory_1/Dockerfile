# Use an official PHP image as the base
FROM php:latest

# Copy your PHP application to the container
COPY . /var/www/html/

# Install any additional dependencies or configurations if needed
# ...

# Copy the MySQL database backup file into the container
COPY SMHOS.sql /docker-entrypoint-initdb.d/SMHOS.sql
