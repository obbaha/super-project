# Stage 1: PHP & Composer
FROM php:8.2-apache AS backend

# Install System Dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev \
    zip unzip git curl libonig-dev libxml2-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# IMPORTANT: Install PHP dependencies first so Filament presets are available
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Node & Vite Build
FROM node:20-slim AS frontend
WORKDIR /app
COPY --from=backend /app .
RUN npm install && npm run build

# Stage 3: Final Production Image
FROM php:8.2-apache
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libzip-dev zip unzip libicu-dev libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Enable Apache Mod Rewrite
RUN a2enmod rewrite

# ... (بعد سطر النسخ من الـ frontend)
COPY --from=frontend /app /var/www/html

# إصلاح المسار والصلاحيات وإنشاء ملف قاعدة البيانات للـ SQLite مؤقتاً
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf \
    && a2enmod rewrite

# إنشاء المجلدات وملف القاعدة وضبط الصلاحيات
RUN mkdir -p /var/www/html/storage/framework/cache/data \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && touch /var/www/html/database/database.sqlite \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/database

WORKDIR /var/www/html
EXPOSE 8080

CMD ["apache2-foreground"]
