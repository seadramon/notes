# Notes API

## Prerequisites

Pastikan sistem Anda sudah memiliki:
- PHP 8.2 atau lebih tinggi
- Composer
- MySQL
- Git

## Installation Steps

### 1. Install Dependencies

Install PHP dependencies menggunakan Composer:

```bash
composer install
```

### 2. Environment Configuration

Copy file environment dan konfigurasi:

```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan dengan konfigurasi database anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. JWT Authentication Setup

Generate JWT secret key:

```bash
php artisan jwt:secret
```

### 6. Database Setup

Buat database baru sesuai dengan nama yang sudah dikonfigurasi di `.env`

Jalankan migrasi database:

```bash
php artisan migrate
```

## Tech Stack

- Laravel 11.x
- PHP 8.2
- tymon/jwt-auth
- MySQL/PostgreSQL
- Composer