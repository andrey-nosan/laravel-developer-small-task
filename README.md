Installation
------------

Make sure you have the PHP >=7.2 and composer are installed.

```bash
$ git clone https://github.com/andrey-nosan/laravel-developer-small-task.git
```
```bash
$ cd laravel-developer-small-task
```
```bash
$ cp .env.example .env
```
Setup database connection:
```dotenv
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
Setup queue driver:
```dotenv
QUEUE_CONNECTION=
```
Setup mailer:
```dotenv
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="
```
Run composer:
```bash
$ composer install
```
Generate key:
```bash
$ php artisan key:generate
```
Create the database and run migrations:
```bash
$ php artisan migrate --seed
```
Generate route list for JS:
```bash
$ php artisan route:json
```
Run npm install:
```bash
$ npm i
```
Run npm build:
```bash
$ npm run dev
```
