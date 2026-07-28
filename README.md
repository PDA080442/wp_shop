# Shop

Интернет-магазин на Laravel: каталог товаров, session-based корзина и оформление заказа без авторизации.

## О проекте

Monolith-приложение с server-side рендерингом (Blade). Пользователь просматривает каталог, добавляет товары в корзину и оформляет заказ, указав имя и email получателя. После подтверждения заказ сохраняется в БД, остатки уменьшаются, корзина очищается.

## Стек


Backend: PHP 8.3+, Laravel 13
Шаблоны: Blade
UI: Tailwind CSS 4, Vite 8
БД: SQLite (MySQL — опционально)
Тесты: PHPUnit 11 (67 тестов)

## Требования

- PHP >= 8.3 с расширениями: pdo, mbstring, openssl, tokenizer, xml, ctype, json, bcmath
- Node.js 18+ и npm (сборка CSS/JS)

## Quickstart

### 1. Клонирование и зависимости

```bash
git clone <repository-url> stor
cd stor

composer install
npm install
npm run build
```

### 2. Окружение

```bash
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
```

В `.env` укажите **абсолютный** путь к SQLite:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/stor/database/database.sqlite
```

### 3. Миграции и данные

```bash
php artisan migrate
php artisan db:seed
```

`db:seed` создаёт тестового пользователя, 12 товаров (2 без остатка) и демо-заказы.

### 4. Запуск

```bash
php artisan serve
```

Откройте [http://127.0.0.1:8000](http://127.0.0.1:8000).

Для hot-reload CSS/JS (опционально, второй терминал):

```bash
npm run dev
```

## Маршруты

Приложение не имеет REST API — все действия через HTML-формы и redirect. Ниже полный список web-маршрутов.

| Метод | URL | Имя | Описание |
|-------|-----|-----|----------|
| GET | `/` | `catalog.index` | Каталог товаров |
| GET | `/products/{product}` | `products.show` | Карточка товара |
| GET | `/cart` | `cart.index` | Просмотр корзины |
| POST | `/cart/add` | `cart.add` | Добавить товар в корзину |
| PATCH | `/cart/{product}` | `cart.update` | Изменить количество позиции |
| DELETE | `/cart/{product}` | `cart.remove` | Удалить позицию из корзины |
| DELETE | `/cart` | `cart.clear` | Очистить корзину |
| GET | `/checkout` | `checkout.index` | Форма оформления заказа |
| POST | `/checkout` | `checkout.store` | Подтвердить и сохранить заказ |
| GET | `/checkout/success/{order}` | `checkout.success` | Страница успешного заказа |

Корзина хранится в сессии (`session('cart')` — массив `product_id => quantity`).

## Модели и связи

### Product (`products`)

| Поле | Тип | Описание |
|------|-----|----------|
| `name` | string | Название |
| `price` | decimal(10,2) | Цена (≥ 0) |
| `stock` | unsigned int | Остаток (≥ 0) |
| `image` | string, nullable | Путь к файлу в `public/` или URL |

Ключевые методы: `isInStock()`, `canOrder(int $quantity)`, `formattedPrice()`, `imageUrl()`.

### Order (`orders`)

| Поле | Тип | Описание |
|------|-----|----------|
| `customer_name` | string | Имя получателя |
| `customer_email` | string | Email (индекс) |
| `total` | decimal(10,2) | Сумма заказа |

Связь: `hasMany(OrderItem::class)` через `items()`.

### OrderItem (`order_items`)

| Поле | Тип | Описание |
|------|-----|----------|
| `order_id` | FK | Заказ |
| `product_id` | FK, nullable | Товар (nullOnDelete при удалении товара) |
| `product_name` | string | Снимок названия на момент заказа |
| `price` | decimal(10,2) | Снимок цены |
| `quantity` | unsigned int | Количество (≥ 1) |

Связи: `belongsTo(Order::class)`, `belongsTo(Product::class)`.

При сохранении/удалении позиции `Order::recalculateTotal()` пересчитывает `orders.total`.


## Artisan-команды

| Команда | Описание |
|---------|----------|
| `php artisan migrate` | Выполнить миграции |
| `php artisan db:seed` | Полный seed: user + 12 товаров + демо-заказы |
| `php artisan products:seed {count=12}` | Создать N случайных товаров (Faker) |
| `php artisan products:seed 12 --fresh` | Очистить `products` и создать 12 заново |
| `php artisan test` | Запустить тесты (67) |
| `php artisan serve` | Локальный dev-сервер |
| `./vendor/bin/pint --test` | Проверка PSR-12 |

### Переменные seed в `.env`

```env
PRODUCT_SEED_COUNT=12      # количество товаров в ProductSeeder
PRODUCT_SEED_FRESH=false   # true — пересоздать товары при db:seed
```

## Тесты

```bash
php artisan test
```

Основные feature-файлы: `CatalogAndProductTest`, `ShoppingCartTest`, `CheckoutTest`. Unit: `CartServiceTest`, `OrderServiceTest`, `ProductCanOrderTest`.

## Структура проекта

```
stor/
├── app/
│   ├── Http/Controllers/    # Product, Cart, Checkout
│   ├── Http/Requests/         # Form Request валидация
│   ├── Models/                # Product, Order, OrderItem
│   ├── Services/              # CartService, OrderService
│   ├── Data/CartItem.php       # DTO позиции корзины
│   └── helpers.php            # cart(), money()
├── database/migrations/
├── database/seeders/
├── resources/views/           # catalog, products, cart, checkout
├── routes/web.php
├── tests/                     # Feature + Unit
```
