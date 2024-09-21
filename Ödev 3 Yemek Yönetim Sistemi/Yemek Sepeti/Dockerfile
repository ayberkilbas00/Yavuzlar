# PHP Apache imajı
FROM php:8.1-apache

# Gerekli PHP eklentilerini yükle
RUN docker-php-ext-install pdo pdo_mysql

# Apache mod_rewrite etkinleştir
RUN a2enmod rewrite

# Proje dosyalarını kopyala
COPY . /var/www/html

# Upload klasörüne yazma izinleri ver
RUN chmod -R 777 /var/www/html/uploads

# Apache'yi başlat
CMD ["apache2-foreground"]
