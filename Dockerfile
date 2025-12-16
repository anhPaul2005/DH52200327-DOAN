FROM php:8.1-apache

# Bật mod_rewrite (nếu cần .htaccess)
RUN a2enmod rewrite

# Cài PHP extension cần cho project (PDO MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Copy toàn bộ source code vào thư mục web
COPY . /var/www/html/

# Phân quyền cho Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
