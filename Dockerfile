# ============================================
# 1. Base Image
# ============================================
FROM php:8.3-fpm

# ============================================
# 2. Working Directory
# ============================================
WORKDIR /var/www/html

# ============================================
# 3. Install Dependencies
# ============================================
RUN apt-get update && apt-get install -y \
    nginx supervisor git unzip curl zip \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql zip bcmath \
    && rm -rf /var/lib/apt/lists/*

# ============================================
# 4. Install Composer
# ============================================
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# ============================================
# 5. Copy Laravel Source Code
# ============================================
COPY . .

# ============================================
# 6. Install Laravel Dependencies
# ============================================
RUN composer install --no-dev --optimize-autoloader

# ===============================
# 6.1 Add Custom PHP Upload Limits <-- BARU
# ===============================
COPY ./docker/custom.ini /usr/local/etc/php/conf.d/custom-upload-limits.ini

# ============================================
# 7. Optimize Laravel
# ============================================
RUN php artisan config:clear && php artisan cache:clear

# ============================================
# 8. Permissions for storage and cache
# ============================================
RUN chown -R www-data:www-data storage bootstrap/cache

# ===============================
# 9. Copy Nginx Config
# ===============================
COPY ./docker/nginx.conf /etc/nginx/sites-available/default

# ===============================
# 10. Copy Supervisor Config
# ===============================
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ===============================
# 11. Expose Port & Start Services
# ===============================
EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]