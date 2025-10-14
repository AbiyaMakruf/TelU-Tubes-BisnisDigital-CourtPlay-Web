# ===============================
# 1. Base Image
# ===============================
FROM php:8.3-fpm

# ===============================
# 2. Set Working Directory
# ===============================
WORKDIR /var/www/html

# ===============================
# 3. Install Dependencies
# ===============================
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    libzip-dev libpq-dev nginx supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath zip \
    && rm -rf /var/lib/apt/lists/*

# ===============================
# 4. Install Composer
# ===============================
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# ===============================
# 5. Copy Project Files
# ===============================
COPY . .

# ===============================
# 6. Install Laravel Dependencies
# ===============================
RUN composer install --no-dev --optimize-autoloader

# ===============================
# 7. Set Permissions
# ===============================
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# ===============================
# 8. Copy Nginx Config
# ===============================
COPY ./docker/nginx.conf /etc/nginx/sites-available/default

# ===============================
# 9. Copy Supervisor Config
# ===============================
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ===============================
# 10. Expose Port & Start Services
# ===============================
EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
