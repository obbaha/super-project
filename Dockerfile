# المرحلة الأولى: بناء ملفات الواجهة (Vite)
FROM node:20-slim AS frontend-builder
WORKDIR /app
COPY . .
RUN npm install && npm run build

# المرحلة الثانية: إعداد بيئة PHP 8.2 (الأكثر استقراراً للإنتاج حالياً)
# ملاحظة: PHP 8.5 ما زالت تجريبية جداً في بيئات الإنتاج، الـ 8.2 ستعمل مع كودك دون مشاكل
FROM php:8.2-apache

# تثبيت متطلبات النظام وإضافات PHP التي يحتاجها Filament و Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev \
    zip unzip git curl libonig-dev libxml2-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# تفعيل خاصية الـ Rewrite في Apache
RUN a2enmod rewrite

# إعداد المجلد الرئيسي للعمل
WORKDIR /var/www/html

# نسخ ملفات المشروع
COPY . .

# نسخ ملفات الـ CSS/JS التي تم بناؤها في المرحلة الأولى
COPY --from=frontend-builder /app/public/build ./public/build

# تثبيت Composer (الأداة الرسمية لإدارة حزم PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# ضبط المجلد الرئيسي لـ Apache ليكون public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# ضبط الصلاحيات للمجلدات التي يحتاج لارافيل الكتابة فيها
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# إعدادات الـ PHP للإنتاج
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

EXPOSE 80
