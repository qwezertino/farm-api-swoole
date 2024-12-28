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
-   If you want you can change some values in `.env` like `APP_PORT`, `FORWARD_REDIS_PORT`, `FORWARD_DB_PORT` etc. By default it's `61, 16379, 33043`

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
-   Clear the Redis cache, it sometimes return empty products on start after building project
    ```php
    docker-compose exec app php artisan cache:clear
    ```

## USEFULL COMMANDS

-   To show logs from service container continously (e.g. `docker-compose logs --follow app`)
    ```sh
    docker-compose logs --follow <service_name>
    ```
-   To sheck all running containers and services
    ```sh
    docker-compose ps
    ```

## HOW TO USE

-   Access to API: `http://localhost:${APP_PORT:-8000}/api/products/`
-   To get, set and e.tc. use `GET, POST, PATCH, DELETE` methods on `http://localhost:${APP_PORT:-8000}/api/products/`
-   For sending JSON data use `Content-Type: application/json` header
-   You can use any app to send API requests as you want (e.g. Postman, Insomnia, ThunderClient in VSCode etc.)
-   Correct format for JSON data is
    ```json
    { "name": "string", "price": "0.0", "amount": 0 }
    ```

## INFO

-   All local changes automatically triggers the server watcher and restarts the server workers.

-   You can use `artisan` commands from inside the container (e.g. `docker-compose exec app php artisan migrate --force`)

-   To completely reset the project and remove old database data + redis data - use commands like
    ```sh
    sudo rm -rf database/postgres-data
    ```
    and
    ```sh
    sudo rm -rf database/redis-data
    ```
    then use
    ```sh
    docker-compose down -v
    ```
    to complete remove docker cache and build and then
    ```sh
    docker-compose up -d --build
    ```
    to rebuild containers
-   This app use Swoole server for HTTP protocol and Redis for cache with PosgreSQL database, and have a huge perfomance. This is result of WRK benchmarks:
    ```sh
    wrk -t4 -c10 -d10s http://localhost:61/api/products/
    ```
    -   `Latency Avg: 10.19ms`
    -   `Req/Sec: 216.28`
    -   `Requests/sec: 860.96`
    -   `Transfer/sec: 5.91MB`
    -   `8618 requests in 10.01s, 59.16MB read`
