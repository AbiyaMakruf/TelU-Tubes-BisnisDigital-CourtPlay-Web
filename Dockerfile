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

# ============================================
# 7. Optimize Laravel
# ============================================
RUN php artisan config:clear && php artisan cache:clear

# ============================================
# 8. Permissions for storage and cache
# ============================================
RUN chown -R www-data:www-data storage bootstrap/cache

# ============================================
# 9. Copy Nginx & Supervisor configs
# ============================================
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ============================================
# 10. Environment Variable for Cloud Run
# ============================================
# Cloud Run expects the container to listen on PORT (default 8080)
ENV PORT=8080

# ============================================
# 11. Expose Port 8080 for Cloud Run
# ============================================
EXPOSE 8080

# ============================================
# 12. Start Supervisor (which runs PHP-FPM + Nginx)
# ============================================
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]