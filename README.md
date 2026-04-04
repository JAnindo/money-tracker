# Money Tracker API

A REST API built with Laravel for managing personal finances with multiple wallets.

## Requirements
- PHP 8.2+
- Composer
- SQLite

## Setup
1. Clone the repository
2. Install dependencies: `composer install`
3. Copy environment file: `cp .env.example .env`
4. Create SQLite database: `touch database/database.sqlite`
5. Run migrations: `php artisan migrate`
6. Start server: `php artisan serve`

## Authentication
This API uses Laravel Sanctum for token-based authentication.

## API Endpoints

### Public Routes (No token required)
| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/register` | Create a new account |
| POST | `/api/login` | Login and get token |

### Protected Routes (Token required)
| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/logout` | Logout current session |
| GET | `/api/profile` | Get user profile with all wallets |
| POST | `/api/wallets` | Create a new wallet |
| GET | `/api/wallets/{id}` | Get wallet with transactions |
| POST | `/api/transactions` | Add income or expense |

## Example Requests

### Register
```json
POST /api/register
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123"
}
```

### Login
```json
POST /api/login
{
  "email": "john@example.com",
  "password": "password123"
}
```

### Create Wallet
```json
POST /api/wallets
Authorization: Bearer YOUR_TOKEN
{
  "user_id": 1,
  "name": "Personal Account"
}
```

### Add Transaction
```json
POST /api/transactions
Authorization: Bearer YOUR_TOKEN
{
  "wallet_id": 1,
  "type": "income",
  "amount": 1000,
  "description": "Salary"
}
```

## Features
- User registration and login with token authentication
- Create multiple wallets per user
- Add income and expense transactions
- Automatic balance calculation
- Insufficient balance protection
- Total balance across all wallets

## Tech Stack
- Laravel 12
- SQLite
- Laravel Sanctum
- PHP 8.2