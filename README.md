# Shop

Интернет-магазин на Laravel: каталог товаров, корзина (session-based) и оформление заказа без авторизации.

## О проекте

Тестовое задание — monolith-приложение с server-side рендерингом. Пользователь просматривает каталог, добавляет товары в корзину и оформляет заказ, указав имя и email получателя.


## Стек технологий


Backend: PHP 8.3+, Laravel 13
Шаблоны: Blade
UI: Tailwind CSS 4 + Vite 
БД: SQLite
Frontend-сборка: Vite 8, npm

## Требования

- PHP >= 8.3 с расширениями: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- Composer 2.x
- Node.js 18+ и npm (для сборки assets)

## Quickstart

### 1. Клонирование и зависимости

```bash
git clone <repository-url> stor
cd stor

composer install
npm install
npm run build
```

### 2. Настройка окружения

```bash
cp .env.example .env
php artisan key:generate
```

```bash
touch database/database.sqlite
```

В `.env` укажите абсолютный путь к файлу БД:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/stor/database/database.sqlite
```

**MySQL (альтернатива):** раскомментируйте блок MySQL в `.env.example`, задайте `DB_CONNECTION=mysql`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

### 3. Миграции и тестовые данные

```bash
php artisan migrate
php artisan db:seed
```

### 4. Запуск

```bash
php artisan serve

npm run dev
```

Откройте [http://127.0.0.1:8000].

## Структура проекта

```
stor/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # ProductController, CartController, CheckoutController
│   │   └── Requests/        # Form Request классы (валидация)
│   ├── Models/              # Eloquent-модели (Product, Order — в разработке)
│   ├── Services/            # Бизнес-логика (CartService, OrderService — в разработке)
│   └── Providers/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── views/
│   │   ├── layouts/         # app.blade.php, header, footer
│   │   ├── catalog/         # главная / каталог
│   │   ├── products/        # карточка товара
│   │   ├── cart/
│   │   └── checkout/
│   ├── css/app.css
│   └── js/app.js
├── routes/web.php
├── tests/                   # Feature и Unit тесты
└── b.md                     # бэклог проекта
```

### Контроллеры

| Контроллер | Назначение |
|------------|------------|
| `ProductController` | Каталог (`/`) и карточка товара (`/products/{id}`) |
| `CartController` | Корзина: просмотр, добавление, обновление, удаление |
| `CheckoutController` | Оформление и сохранение заказа |

### Маршруты

| Метод | URI | Имя | Описание |
|-------|-----|-----|----------|
| GET | `/` | `catalog.index` | Каталог товаров |
| GET | `/products/{id}` | `products.show` | Карточка товара |
| GET | `/cart` | `cart.index` | Корзина |
| POST | `/cart/add` | `cart.add` | Добавить в корзину |
| PATCH | `/cart/update` | `cart.update` | Изменить количество |
| DELETE | `/cart/remove` | `cart.remove` | Удалить позицию |
| GET | `/checkout` | `checkout.index` | Оформление заказа |
| POST | `/checkout` | `checkout.store` | Подтвердить заказ |

### Artisan-команды

| Команда | Описание |
|---------|----------|
| `php artisan migrate` | Выполнить миграции |
| `php artisan db:seed` | Заполнить БД (тестовый user + ProductSeeder) |
| `php artisan products:seed {count=50}` | Заполнить товарами (faker) |
| `php artisan products:seed 100 --fresh` | Очистить products и создать 100 |
| `php artisan test` | Запустить тесты |
| `php artisan serve` | Локальный dev-сервер |

## Функциональность

### Каталог (`/`)

Главная страница со списком товаров: название, цена, остаток. Пагинация и карточки — EP3.

### Карточка товара (`/products/{id}`)

Полная информация о товаре, выбор количества, кнопка «В корзину». При `stock = 0` — добавление заблокировано.

### Корзина (`/cart`)

Session-based хранение без авторизации. Просмотр позиций, изменение количества, удаление, итоговая сумма.

### Оформление заказа (`/checkout`)

Форма: имя получателя и email. Сводка заказа. После подтверждения — запись в `orders` и `order_items`, уменьшение `stock`, очистка корзины.

### Ограничение по остатку

- `stock = 0` → товар помечен «Нет в наличии», кнопка недоступна
- `quantity > stock` → ошибка валидации на всех этапах (каталог, корзина, checkout)

## Тесты

```bash
php artisan test
```

Сейчас включены стандартные примеры Laravel. Feature-тесты каталога, корзины и checkout — EP8.
