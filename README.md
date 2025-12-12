# Super Wallet

Full Stack challenge provided by Adriano Cobuccio company

<p>Available at: <a href="https://superwallet.infinityfreeapp.com/" target="_blank">https://superwallet.infinityfreeapp.com/</a></p>

### Technologies Used 💻

* Docker
* PHP 8.5
* Node.js v24.11.1
* MySQL
* Laravel
* Vite
* Tailwind
* Htmx
* Javascript

## Instructions for Running Locally 🚀

This repository contains the necessary Docker files to run the development environment.

## Prerequisites 📋

* Docker: make sure it is installed on your machine.

## File Configuration `.env` 🛠️

This project uses **two `.env` files**:

1. **Root `.env`** — used by Docker and general environment configuration.
2. **`/application/.env`** — used specifically by Laravel for application-level configuration.

### Steps:

1. **Root `.env`**

   * Locate `.env.example` in the root directory.
   * Copy it and rename to `.env`.
   * Open the file and set environment variables related to Docker, database connection, ports, etc.
   * Save the file.

2. **Laravel `/application/.env`**

   * Navigate to `/application` folder.
   * Copy `.env.example` to `.env`.
   * Update Laravel-specific variables such as:

   ```env
   APP_NAME=WpImageLibrary
   APP_ENV=local
   APP_KEY= # leave empty, will generate later
   APP_DEBUG=true
   APP_URL=http://localhost:8095

   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=root
   DB_PASSWORD=root
   ```

   * Save the file.

> ⚠️ Make sure the database credentials in both `.env` files match your Docker `docker-compose.yml` configuration.

## How to Use 🛠️

1. Navigate to the root directory of the project.
2. Make sure your application structure includes all necessary files such as `package.json`, `src`, and `/application`.
3. Build and start the Docker containers:

```bash
docker-compose up -d --build
```

## Access the Container

From the root directory, run:

```bash
docker-compose exec -it app sh
```

## Composer Commands

Inside the container:

```bash
composer install
composer dump-autoload -o
```

## Run Front-End Scripts

```bash
npm install
npm run build
```

## Generate a New Laravel Key

Inside `/application` folder (or container):

```bash
php artisan key:generate
```

## Run Migrations and Seeders

```bash
php artisan migrate:fresh --seed
```

## Access the Application

Locally, the application will be available at: [http://localhost:8095/](http://localhost:8095/)