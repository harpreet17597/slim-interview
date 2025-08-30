# SlimPHP Project

## 📚 Overview

This is a **SlimPHP** project. The following instructions will help you set up and run the project locally.

---

## ⚙️ Setup Instructions

### 1. Setup Environment

Copy the example environment file and update database credentials:

```bash
cp .env.example .env
```

Then edit the `.env` file to configure your **database credentials**.

---

### 2. Configure Database for Migrations

Update the database credentials in `phinx.php` for running migrations and seeders. Example configuration:

```php
// phinx.php
return [
    'paths' => [
        'migrations' => 'db/migrations',
        'seeds' => 'db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'your_database_name',
            'user' => 'your_database_user',
            'pass' => 'your_database_password',
            'port' => 3306,
            'charset' => 'utf8',
        ],
    ],
];
```

This ensures that **Phinx migrations** and **seeders** work correctly with your database.

---

### 3. Install Dependencies

Install PHP dependencies via Composer:

```bash
composer install
```

---

### 3. Run Setup Script

Make the setup script executable and run it:

```bash
chmod +x setup.sh
./setup.sh
```

---

### 5. Run the SlimPHP App

Start the local development server:

```bash
php -S localhost:8080 -t public
```

Your app will now be accessible at: [http://localhost:8080](http://localhost:8080)

---

## 🧱 API Endpoints

| Method | Endpoint                  | Auth Required | Description                              |
| ------ | ------------------------- | ------------- | ---------------------------------------- |
| POST   | `/oauth/token`            | ❌            | Issue an OAuth2 token via password grant |
| POST   | `/books`                  | ✅            | Add a new book                           |
| GET    | `/books`                  | ✅            | List all books                           |
| POST   | `/books/{bookId}/borrow`  | ✅            | Record a user borrowing a book           |
| GET    | `/books/{bookId}/borrows` | ✅            | List borrow logs for a given book        |

> All protected endpoints must use a Bearer token:  
> `Authorization: Bearer <access_token>`

---

### 5. Analytics Endpoints

Purpose: Return the most recent borrow log for each book.

```bash
1. GET /analytics/latest-borrow-per-book
```

Purpose: Return each borrow action ranked chronologically for each user-book pair.

```bash
2. GET /analytics/borrow-rank-per-user
```

Purpose: Return a summary of each book, including:

```bash
3. GET /analytics/book-summary
```
