# ICP Portal — Inventory & Order Management API

A RESTful API built with Laravel 13 for managing inventory and orders. Built as part of a technical assessment demonstrating clean architecture, API authentication, and transactional order processing.

---

## Tech Stack

- **Framework**: Laravel 13
- **PHP**: 8.4
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum (Token-based)
- **Testing**: Pest PHP

---

## Architecture Decisions

- **Service Classes** — Business logic lives in `app/Services/` keeping controllers thin and focused on HTTP concerns only
- **Form Requests** — All validation handled via dedicated `app/Http/Requests/` classes
- **Eloquent Resources** — Consistent JSON response structure via `app/Http/Resources/`
- **Database Transactions** — Order creation uses `DB::transaction()` with pessimistic locking (`lockForUpdate()`) to prevent race conditions and overselling
- **PSR-12** — Code follows PSR-12 coding standards throughout

---

## Requirements

- PHP 8.4+
- MySQL 8.0+
- Composer

---

## Setup Instructions

### 1. Clone the repository

```bash
git clone git@github.com:YOUR_USERNAME/icp-portal.git
cd icp-portal
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=icp_portal
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Run migrations and seed database

```bash
php artisan migrate --seed
```

This will create:
- 1 test user (`test@example.com` / `password`)
- 5 categories
- 50 products (10 per category)

### 5. Start the development server

```bash
php artisan serve
```

API is available at `http://localhost:8000/api`

---

## API Endpoints

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | Register a new user |
| POST | `/api/login` | Login and get token |
| POST | `/api/logout` | Logout (requires auth) |
| GET | `/api/me` | Get authenticated user |

### Products

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/products` | List all products (paginated) |
| GET | `/api/products?search=sku` | Search by name or SKU |
| GET | `/api/products?category_id=1` | Filter by category |
| POST | `/api/products` | Create a product |
| GET | `/api/products/{id}` | Get a product |
| PUT | `/api/products/{id}` | Update a product |
| DELETE | `/api/products/{id}` | Delete a product |

### Orders

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/orders` | List user's orders |
| POST | `/api/orders` | Create an order |
| GET | `/api/orders/{id}` | Get an order |
| PATCH | `/api/orders/{id}/cancel` | Cancel an order |

### Categories

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/categories` | List all categories |

---

## Authentication

All endpoints except `/api/login` and `/api/register` require a Bearer token.

```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'

# Use token in subsequent requests
curl http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Example Order Request

```json
POST /api/orders
{
  "items": [
    { "product_id": 1, "quantity": 2 },
    { "product_id": 3, "quantity": 1 }
  ]
}
```

---

## Running Tests

```bash
php artisan test
```

### Test Coverage

| Test | Description |
|------|-------------|
| `it cannot order out of stock items` | Returns 422 when stock is 0 |
| `it decrements stock correctly after order` | Stock reduces by ordered quantity |
| `it creates order with correct total amount` | Total calculated correctly |
| `it can list products` | Returns paginated product list |
| `it can filter products by category` | Filters correctly by category_id |
| `it can search products by name or sku` | Search returns matching results |

---

## Database Schema