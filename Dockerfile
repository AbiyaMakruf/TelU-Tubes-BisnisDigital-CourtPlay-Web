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

# ===============================
# 8. Copy SSL Files (NEW STEP)
# ===============================
# Salin file SSL ke direktori yang aman di dalam container
# Anda harus mempastikan file ini ada di path ./docker/ssl/
COPY ./docker/ssl/certificate.crt /etc/nginx/ssl/
COPY ./docker/ssl/ca_bundle.crt /etc/nginx/ssl/
COPY ./docker/ssl/private.key /etc/nginx/ssl/

# Gabungkan certificate.crt dan ca_bundle.crt menjadi satu file bundle (standar praktik NGINX)
# Menggunakan ca_bundle.crt adalah opsional jika NGINX Anda dapat menggunakan kedua file secara terpisah, 
# tetapi menggabungkannya seringkali lebih sederhana.
RUN cat /etc/nginx/ssl/certificate.crt /etc/nginx/ssl/ca_bundle.crt > /etc/nginx/ssl/fullchain.crt

# ===============================
# 9. Copy Nginx Config
# ===============================
COPY ./docker/nginx.conf /etc/nginx/sites-available/default
# Buat symlink agar NGINX menggunakannya
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# ===============================
# 10. Copy Supervisor Config
# ===============================
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ===============================
# 11. Expose Port & Start Services
# ===============================
EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]