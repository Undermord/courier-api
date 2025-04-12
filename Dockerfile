FROM yiisoftware/yii2-php:7.4-apache

# Включаем mod_rewrite для Apache
RUN a2enmod rewrite

# Устанавливаем рабочую директорию
WORKDIR /app

# Копируем конфигурацию Apache
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Перезапускаем Apache
RUN service apache2 restart 