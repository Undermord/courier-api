# REST API для управления курьерами и транспортом

## Описание
REST API для управления курьерами, транспортными средствами и заявками курьеров, разработанное на фреймворке Yii2.

## Требования
- PHP 7.4 или выше
- MySQL 5.7 или выше
- Composer
- Веб-сервер (рекомендуется Nginx)
- Docker и Docker Compose (опционально)

## Установка (через Docker)

1. Клонируйте репозиторий:
```bash
git clone [url-репозитория]
cd [имя-директории]
```

2. Запустите проект с Docker Compose:
```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

3. Инициализируйте проект и установите зависимости:
```bash
docker exec courier-api-php-1 php /app/init --env=Development --overwrite=All
docker exec courier-api-php-1 composer install -d /app
```

> **Примечание**: Конфигурация базы данных уже настроена для работы с Docker. Подключение настроено на хост `db`, с именем пользователя `yii` и паролем `yii`.

4. Запустите миграции для создания таблиц в базе данных и заполнения демо-данными:
```bash
docker exec courier-api-php-1 php /app/yii migrate/fresh --interactive=0
```

5. API будет доступно по адресу http://localhost:8080/
6. PhpMyAdmin доступен по адресу http://localhost:8081/ (логин: yii, пароль: yii)

## Установка (стандартная)

1. Клонируйте репозиторий:
```bash
git clone [url-репозитория]
cd [имя-директории]
```

2. Установите зависимости через Composer:
```bash
composer install
```

3. Создайте базу данных MySQL:
```sql
CREATE DATABASE courier_service CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

4. Настройте подключение к базе данных в файле `common/config/main-local.php`:
```php
'db' => [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=courier_service',
    'username' => 'ваш_пользователь',
    'password' => 'ваш_пароль',
    'charset' => 'utf8',
],
```

5. Примените миграции:
```bash
php yii migrate
```

## API Endpoints

### Курьеры 
- GET /courier - список курьеров
  - Параметры:
    - role (опционально) - фильтрация по роли (main/basic)
- POST /courier - создание курьера
- GET /courier/{id} - просмотр курьера
- PUT /courier/{id} - обновление курьера
- DELETE /courier/{id} - удаление курьера

### Транспорт 
- GET /vehicle - список транспортных средств
  - Параметры:
    - courier_id (опционально) - фильтрация по ID курьера
    - type (опционально) - фильтрация по типу (car/scooter)
    - expand=courier - включить информацию о курьере
- POST /vehicle - создание транспортного средства
- GET /vehicle/{id} - просмотр транспортного средства
- PUT /vehicle/{id} - обновление транспортного средства
- DELETE /vehicle/{id} - удаление транспортного средства

### Заявки 
- GET /request - список заявок
  - Параметры:
    - courier_id (опционально) - фильтрация по ID курьера
    - sort (опционально) - сортировка (например: -created_at для сортировки по убыванию)
- POST /request - создание заявки
- GET /request/{id} - просмотр заявки
- PUT /request/{id} - обновление заявки
- DELETE /request/{id} - мягкое удаление заявки

## Аутентификация
API использует аутентификацию через заголовок X-Api-Key. Для примера можно использовать тестовый ключ: `test-api-key`. Только пользователи с ролью 'main' могут выполнять операции POST, PUT и DELETE.

## Валидации
- Email курьера должен быть уникальным
- Поля first_name, last_name, email обязательны
- Серийный номер транспорта должен быть уникальным
- У одного курьера может быть максимум один автомобиль и один скутер
- У курьера может быть только одна активная заявка (статус 'started')

## Формат ответов
Все ответы возвращаются в формате JSON. В случае ошибки:
```json
{
    "status": "error",
    "message": "Текст ошибки"
}
```

## Запуск тестов

В проекте реализован юнит-тест для CourierController.

### Инструкции по запуску тестов:

```bash
docker exec courier-api-php-1 bash -c "cd /app/api && ../vendor/bin/phpunit"
```

### Описание тестов:

Тест `api/tests/unit/controllers/BasicCourierTest.php` проверяет:

1. Корректное создание экземпляра контроллера
2. Правильную настройку поведений для REST API
3. Правильную настройку класса модели

## Возможные проблемы и их решения

### Структура Docker-контейнеров
Проект использует следующие контейнеры:
1. `nginx` - веб-сервер Nginx для обработки HTTP-запросов
2. `php` - PHP-FPM для обработки PHP-скриптов
3. `db` - MySQL для хранения данных
4. `phpmyadmin` - интерфейс для управления базой данных

### Диагностика проблем
Если возникают проблемы при запуске проекта:

1. Убедитесь, что порт 8080 не занят другим приложением
2. Проверьте, что все контейнеры запущены:
   ```bash
   docker-compose ps
   ```
3. Проверьте логи Nginx и PHP:
   ```bash
   docker-compose logs nginx
   docker-compose logs php
   ```

### Конфигурация Nginx
Конфигурация Nginx находится в файле `docker/nginx/default.conf`. Она оптимизирована для работы с Yii2 и включает:
- Обработку статических файлов
- Перенаправление запросов на PHP-FPM
- Защиту от доступа к системным файлам
- Оптимизацию для производительности
