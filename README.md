# Products API

API для управления товарами с поддержкой фильтрации, поиска, сортировки и пагинации. Реализован на Laravel с использованием паттерна Filter.

## 🛠 Технологии

- PHP 8.2
- Laravel 10.x
- MySQL 8.0
- Docker / Docker Compose
- Swagger/OpenAPI 3.0
- Nginx

## ⚡ Функциональность

- ✅ Поиск товаров по названию (подстрока)
- ✅ Фильтрация по цене (диапазон)
- ✅ Фильтрация по категории
- ✅ Фильтрация по наличию на складе
- ✅ Фильтрация по рейтингу
- ✅ Сортировка (по цене, рейтингу, новизне)
- ✅ Пагинация
- ✅ Swagger/OpenAPI документация
- ✅ Docker контейнеризация

## 🚀 Установка и запуск

### С Docker (рекомендуемый способ)

#### 1. Клонирование репозитория

`git clone https://github.com/savckovalexei/laravel-products-api.git`

`laravel-products-api`

#### 2. Настройка окружения

`cp .env.example .env`

Отредактируйте .env при необходимости (по умолчанию настройки уже под Docker).

#### 3. Запуск контейнеров

`docker-compose up -d --build`

#### 4. Установка PHP зависимостей

`docker-compose exec app composer install`

 Генерация ключа
 
`docker-compose exec app php artisan key:generate`

Выполнение миграций и сидеров

`docker-compose exec app php artisan migrate --seed`

Генерация Swagger документации

`docker-compose exec app php artisan l5-swagger:generate`

Скопировать в public для доступа через веб

`docker-compose exec app cp storage/api-docs/api-docs.json public/docs.json`

#### 5. Доступ к приложению

API: http://localhost:8080/api/products

Swagger UI: http://localhost:8080/swagger-final.html

JSON спецификация: http://localhost:8080/docs/api-docs.json

phpMyAdmin: http://localhost:8081 (сервер: mysql, пользователь: root, пароль: root_password)

#### 6. Остановка контейнеров

`docker-compose down`


### Без Docker

#### Требования
PHP >= 8.2

Composer

MySQL >= 8.0

Nginx / Apache

#### 1. Клонирование репозитория

`git clone https://github.com/savckovalexei/laravel-products-api.git`

`cd laravel-products-api`

#### 2. Установка зависимостей

`composer install`

#### 3. Настройка окружения

`cp .env.example .env`

`php artisan key:generate`

Отредактируйте .env для подключения к базе данных:

DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=products_db

DB_USERNAME=root

DB_PASSWORD=your_password

#### 4. Настройка базы данных

Создайте базу данных в MySQL

`mysql -u root -p -e "CREATE DATABASE products_db;"`

Выполните миграции

`php artisan migrate`

(Опционально) Заполните тестовыми данными

`php artisan db:seed`

#### 5. Генерация Swagger документации

`php artisan l5-swagger:generate`

Скопировать в public для доступа через веб

`cp storage/api-docs/api-docs.json public/docs.json`

`http://localhost:8080/docs/api-docs.json`

#### 6. Запуск сервера

`php artisan serve`

Приложение будет доступно по адресу: http://localhost:8000


## 📡 API Endpoints

#### GET /api/products
Получение списка товаров с фильтрацией и пагинацией.

URL: `http://localhost:8080/api/products` (Docker) или `http://localhost:8000/api/products` (локально)

Method: GET

#### 📊 Параметры фильтрации

| Параметр | Тип | Описание | Пример |
|----------|-----|----------|--------|
| `q` | string | Поиск по названию (подстрока) | `?q=iPhone` |
| `price_from` | float | Минимальная цена | `?price_from=1000` |
| `price_to` | float | Максимальная цена | `?price_to=50000` |
| `category_id` | int | ID категории | `?category_id=1` |
| `in_stock` | bool | Наличие (1/0, true/false) | `?in_stock=true` |
| `rating_from` | float | Минимальный рейтинг (0-5) | `?rating_from=4` |
| `sort` | string | Сортировка | `?sort=price_asc` |
| `page` | int | Номер страницы | `?page=2` |
| `per_page` | int | Элементов на странице (макс 100) | `?per_page=15` |

#### Доступные значения сортировки:

`price_asc` - по возрастанию цены

`price_desc` - по убыванию цены

`rating_desc` - по убыванию рейтинга

`newest` - сначала новые (по умолчанию)


#### 📖 Swagger документация
##### Локально
После запуска приложения Swagger UI доступен по адресу:

Docker: `http://localhost:8080/swagger-final.html`

Локально: `http://localhost:8000/swagger-final.html`


#### 🔍 Примеры запросов

##### Базовые запросы

Все товары с пагинацией (по 15 на странице)

`curl "http://localhost:8080/api/products"`

Вторая страница по 10 товаров

`curl "http://localhost:8080/api/products?page=2&per_page=10"`

##### Поиск и фильтрация

Поиск по названию

`curl "http://localhost:8080/api/products?q=iPhone"`

Фильтр по цене

`curl "http://localhost:8080/api/products?price_from=10000&price_to=50000"`

Только в наличии

`curl "http://localhost:8080/api/products?in_stock=true"`

По категории

`curl "http://localhost:8080/api/products?category_id=1"`

По рейтингу

curl "http://localhost:8080/api/products?rating_from=4.5"

##### Сортировка

По возрастанию цены

`curl "http://localhost:8080/api/products?sort=price_asc"`

По убыванию цены

`curl "http://localhost:8080/api/products?sort=price_desc"`

По рейтингу (сначала лучшие)

`curl "http://localhost:8080/api/products?sort=rating_desc"`

Новинки

`curl "http://localhost:8080/api/products?sort=newest"`

##### Комбинированные запросы

Поиск + цена + сортировка

`curl "http://localhost:8080/api/products?q=ноутбук&price_from=30000&price_to=100000&sort=price_asc"`

Категория + в наличии + рейтинг

`curl "http://localhost:8080/api/products?category_id=1&in_stock=true&rating_from=4"`

Все параметры сразу

`curl "http://localhost:8080/api/products?q=phone&price_from=1000&price_to=100000&category_id=1&in_stock=true&rating_from=3.5&sort=rating_desc&page=1&per_page=20"`

##### Пример ответа

```json
{
  "current_page": 1,
  "per_page": 15,
  "total": 75,
  "data": [
    {
      "id": 1,
      "name": "iPhone 15 Pro Max 256GB",
      "price": 99999.99,
      "in_stock": true,
      "rating": 4.8,
      "category": {
        "id": 1,
        "name": "Электроника"
      }
    }
  ]
}
```
