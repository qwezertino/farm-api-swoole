# Farm RESTFUL API Laravel x Swoole

## !!!ATTENTION!!!

Dependencies: `php`, `composer`, `docker`, `docker-compose`

## INSTALLATION

-   Clone repo
    ```php
    git clone git@github.com:qwezertino/farm-api-swoole.git
    ```
-   Enter the app folder and Copy .env.example to .env
    ```php
    cd farm-api-swoole && cp .env.example .env
    ```
-   If you want you can change some values in .env like `APP_PORT`, `FORWARD_REDIS_PORT`, `FORWARD_DB_PORT` etc. By default it's `61, 16379, 33043`

-   Run composer installation
    ```php
    composer install
    ```
-   Create new app key
    ```php
    php artisan key:generate
    ```
-   Run & build docker containers
    ```php
    docker-compose up -d --build
    ```
-   Run the migrations and seed the database inside docker container
    ```php
    docker-compose exec app php artisan migrate --force && docker-compose exec app php artisan db:seed --class=ProductsTableSeeder
    ```

## USEFULL COMMANDS

-   `docker-compose logs --follow <container_name>` - show logs from service container continously (e.g. `docker-compose logs --follow app`)

## HOW TO USE

-   Access to API: `http://localhost:${APP_PORT:-8000}/api/products/`
-   To get, set and e.tc. use `GET, POST, PATCH, DELETE` methods on `http://localhost:${APP_PORT:-8000}/api/products/`
-   For sending JSON data use `Content-Type: application/json` header
-   You can use any app to send API requests as you want (e.g. Postman, Insomnia, ThunderClient in VSCode etc.)
-   Correct format for JSON data is `{"name": "string", "price": "0.0", "amount": 0}`

## INFO

-   All local changes automatically triggers the server watcher and restarts the server workers.

-   You can use `artisan` commands from inside the container (e.g. `docker-compose exec app php artisan migrate --force`)

-   To completely reset the project and remove old database data + redis data - use commands like `sudo rm -rf database/postgres-data` and `sudo rm -rf database/redis-data` then use `docker-compose down -v` to complete remove docker cache and build and then `docker-compose up -d --build` to rebuild containers
