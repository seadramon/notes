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
- MySQL
- Composer

## Membuat Environment di POSTMAN

### Metode 1: Melalui Sidebar

1. Buka aplikasi Postman
    
2. Di sidebar kiri, klik **"Environments"**
    
3. Klik tombol **"+"** atau **"Create Environment"**
    
4. Beri nama environment, contoh: `local`
    

### Metode 2: Melalui Settings Icon

1. Klik icon gear (⚙️) di pojok kanan atas
    
2. Pilih **"Manage Environments"**
    
3. Klik **"Add"**
    
4. Beri nama environment
    

## Langkah 2: Menambahkan Variables

Tambahkan variabel berikut:

### Basic Variables

| Variable | Initial Value | Current Value | Description |
| --- | --- | --- | --- |
| `host-v1` | `http://localhost:8000/api/v1/` | `http://localhost:8000/api/v1/` | Base URL aplikasi |

### Cara Menambahkan Variables:

1. Di form environment, klik area "Variable"
    
2. Masukkan nama variable (contoh: `host-v1`)
    
3. Masukkan "Initial Value" (nilai default)
    
4. Masukkan "Current Value" (nilai yang sedang digunakan)
    
5. Klik tombol **"Save"**
    

## Langkah 3: Mengaktifkan Environment

1. Klik dropdown di pojok kanan atas (biasanya bertulisan "No Environment")
    
2. Pilih environment yang baru dibuat (contoh: `local`)
    
3. Environment akan aktif dan siap digunakan
    

## Langkah 4: Menggunakan Variables dalam Request

### Menggunakan Variable di URL

Ganti URL hardcode dengan variable:

**Sebelum:**

```
http://localhost:8000/api/v1/login

 ```

**Sesudah:**

```
{{host-v1}}login

 ```
