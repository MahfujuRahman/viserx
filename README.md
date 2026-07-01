# Product CRUD App

A Laravel 12 + Vue 3 single-page application for managing products and categories with JWT authentication and queued email notifications.

## Features

- Product CRUD with `name`, `description`, `price`, and `category_id`
- Category CRUD
- JWT auth for login, register, logout, and current user endpoints
- Product list shows the category name
- Queue job sends an email when a product is created or updated
- Vue 3 Composition API + Pinia + Tailwind CSS
- Repository pattern for backend data access

## Tech Stack

- Backend: Laravel 12
- Auth: `tymon/jwt-auth`
- Frontend: Vue 3, Pinia, Vue Router, Vite
- Styling: Tailwind CSS
- Queue: Database queue driver

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and npm
- SQLite enabled in PHP

## First-Time Setup

If you copied this project from GitHub and want to run it locally, follow these steps in order.

### 1. Clone the project

```bash
git clone <your-repo-url>
cd viserx
```

### 2. Install backend and frontend dependencies

```bash
composer install
npm install
```

### 3. Create the environment file

If `.env` does not exist yet, copy it from the example file:

```bash
copy .env.example .env
```

### 4. Generate the application key

```bash
php artisan key:generate
```

### 5. Configure `.env`

Update these values in `.env`:

```env
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database
MAIL_MAILER=log
VITE_APP_NAME="Product CRUD App"
```

If you want the frontend to call Laravel directly during local development, you can also set:

```env
VITE_API_BASE_URL=http://127.0.0.1:8000/api
FRONTEND_URL=http://127.0.0.1:5173
VITE_DEV_ORIGIN=http://127.0.0.1:5173
```

### 6. Create the SQLite database file

Make sure this file exists:

```text
database/database.sqlite
```

If it is missing, create it with:

```powershell
New-Item -ItemType File database/database.sqlite -Force
```

### 7. Generate the JWT secret

```bash
php artisan jwt:secret
```

### 8. Run migrations and seed the database

```bash
php artisan migrate --seed
```

This will create the database tables and seed demo data.

### 9. Build the frontend assets

```bash
npm run build
```

For local development, you can skip the build and use the Vite dev server instead.

## Run the Project

Open two terminals.

### Terminal 1: start Laravel

```bash
php artisan serve
```

### Terminal 2: start Vite

```bash
npm run dev
```

### Optional Terminal 3: run the queue worker

The product create/update email notification is queued, so run a worker in a third terminal:

```bash
php artisan queue:work
```

Open the app at:

```text
http://127.0.0.1:8000
```

## One-Command Setup

There is also a Laravel setup script defined in `composer.json`:

```bash
composer setup
```

That script installs dependencies, creates `.env` if needed, generates the app key, runs migrations, installs frontend packages, and builds the frontend.

After that, still run:

```bash
php artisan jwt:secret
php artisan migrate --seed
```

Use those commands if you want the JWT auth secret and the demo seed data.

## Demo Credentials

The seeder creates a demo user:

- Email: `demo@example.com`
- Password: `password`

It also seeds sample categories and products so the UI has data right away.

## API Endpoints

Base URL:

```text
/api
```

### Authentication

`POST /api/auth/register`

Request:

```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

Response:

```json
{
  "message": "User created successfully",
  "user": {
    "id": 1,
    "name": "Jane Doe",
    "email": "jane@example.com"
  },
  "token": "jwt-token-here",
  "token_type": "bearer"
}
```

`POST /api/auth/login`

Request:

```json
{
  "email": "demo@example.com",
  "password": "password"
}
```

Response:

```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "Demo Admin",
    "email": "demo@example.com"
  },
  "token": "jwt-token-here",
  "token_type": "bearer"
}
```

`POST /api/auth/logout`

Headers:

```http
Authorization: Bearer jwt-token-here
```

Response:

```json
{
  "message": "Logged out successfully"
}
```

`GET /api/auth/me`

Response:

```json
{
  "data": {
    "id": 1,
    "name": "Demo Admin",
    "email": "demo@example.com"
  }
}
```

### Categories

`GET /api/categories`

Response:

```json
{
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "description": "Devices, gadgets, and accessories.",
      "products_count": 2,
      "created_at": "2026-07-01T...",
      "updated_at": "2026-07-01T..."
    }
  ]
}
```

`POST /api/categories`

Headers:

```http
Authorization: Bearer jwt-token-here
Content-Type: application/json
```

Request:

```json
{
  "name": "Books",
  "description": "Reading and learning"
}
```

### Products

`GET /api/products`

Response:

```json
{
  "data": [
    {
      "id": 1,
      "name": "Wireless Keyboard",
      "description": "A slim wireless keyboard with quiet keys.",
      "price": 59.99,
      "category_id": 2,
      "category_name": "Office",
      "category": {
        "id": 2,
        "name": "Office",
        "description": "Work essentials for a modern workspace.",
        "products_count": 1
      }
    }
  ]
}
```

`POST /api/products`

Headers:

```http
Authorization: Bearer jwt-token-here
Content-Type: application/json
```

Request:

```json
{
  "name": "Standing Desk Lamp",
  "description": "Adjustable LED desk lamp",
  "price": 34.5,
  "category_id": 2
}
```

`PUT /api/products/{id}`

`DELETE /api/products/{id}`

## Notes

- The frontend is a Vue SPA served through Laravel's `resources/views/app.blade.php`.
- Product create/update dispatches a queued job that sends an email using the configured mail driver.
- The backend follows the repository pattern via `app/Repositories` and `app/Interfaces`.

## Quick Verification

```bash
php artisan test
npm run build
```
