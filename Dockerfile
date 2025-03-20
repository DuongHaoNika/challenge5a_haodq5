# Chọn image PHP với Apache server
FROM php:8.1-apache

# Cài đặt các extensions cần thiết cho MySQL và PHP
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql

# Kích hoạt mod_rewrite của Apache để hỗ trợ URL đẹp
RUN a2enmod rewrite

# Copy mã nguồn của dự án vào thư mục của Apache
COPY . /var/www/html/

# Chỉnh sửa quyền thư mục để Apache có thể truy cập
RUN chown -R www-data:www-data /var/www/html

# Cấu hình Apache (nếu cần)
# COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Expose port 80 cho Apache
EXPOSE 80

# Chạy Apache
CMD ["apache2-foreground"]
