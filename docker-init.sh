#!/bin/bash

# Ждем запуска MySQL
echo "Ожидание запуска MySQL..."
until mysql -h db -u yii -pyii -e "SELECT 1"; do
    echo "Waiting for MySQL to be ready..."
    sleep 2
done
echo "MySQL готов!"

# Устанавливаем права на файлы
mkdir -p api/runtime api/web/assets
chmod -R 777 api/runtime
chmod -R 777 api/web/assets
chmod 755 api/web/index.php

# Инициализируем проект
php init --env=Development --overwrite=All

# Применяем миграции
php yii migrate --interactive=0

echo "Инициализация завершена."
echo "API доступен по адресу http://localhost:8080/"
echo "Swagger документация доступна по адресу http://localhost:8080/swagger-ui.html"
echo "Тестовый API ключ: test-api-key (установлен в api/config/params-local.php)" 