# REST API для управления курьерами и транспортом

## Описание
REST API для управления курьерами, транспортными средствами и заявками курьеров, разработанное на фреймворке Yii2.

## Требования
- PHP 7.4 или выше
- MySQL 5.7 или выше
- Composer
- Веб-сервер (Apache/Nginx)
- Docker и Docker Compose (опционально)

## Установка (через Docker)

1. Клонируйте репозиторий:
```bash
git clone [url-репозитория]
cd [имя-директории]
```

2. Запустите проект с Docker Compose:
```bash
docker-compose up -d
```

3. Запустите миграции для создания таблиц в базе данных и заполнения демо-данными:
```bash
docker-compose exec php php yii migrate/fresh --interactive=0
```

4. API будет доступен по адресу http://localhost:8080/api/
5. PhpMyAdmin доступен по адресу http://localhost:8081/ (логин: yii, пароль: yii)

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

### Курьеры (/api/courier)
- GET /api/courier - список курьеров
  - Параметры:
    - role (опционально) - фильтрация по роли (main/basic)
- POST /api/courier - создание курьера
- GET /api/courier/{id} - просмотр курьера
- PUT /api/courier/{id} - обновление курьера
- DELETE /api/courier/{id} - удаление курьера

### Транспорт (/api/vehicle)
- GET /api/vehicle - список транспортных средств
  - Параметры:
    - courier_id (опционально) - фильтрация по ID курьера
    - type (опционально) - фильтрация по типу (car/scooter)
    - expand=courier - включить информацию о курьере
- POST /api/vehicle - создание транспортного средства
- GET /api/vehicle/{id} - просмотр транспортного средства
- PUT /api/vehicle/{id} - обновление транспортного средства
- DELETE /api/vehicle/{id} - удаление транспортного средства

### Заявки (/api/request)
- GET /api/request - список заявок
  - Параметры:
    - courier_id (опционально) - фильтрация по ID курьера
    - sort (опционально) - сортировка (например: -created_at для сортировки по убыванию)
- POST /api/request - создание заявки
- GET /api/request/{id} - просмотр заявки
- PUT /api/request/{id} - обновление заявки
- DELETE /api/request/{id} - мягкое удаление заявки

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
docker-compose exec php bash -c "cd /app/api && ../vendor/bin/phpunit"
```

### Описание тестов:

Тест `api/tests/unit/controllers/BasicCourierTest.php` проверяет:

1. Корректное создание экземпляра контроллера
2. Правильную настройку поведений для REST API
3. Правильную настройку класса модели
