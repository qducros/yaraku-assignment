# CRUD book project
Technical assignment for Yaraku interview process.

This project was made using Laravel, Livewire, Docker, Mysql and Bulma. The tests were done with PHPUnit.

## Table of Contents
- [Description](#description)
- [Local Installation](#local-installation)
- [Production deployment](#production-deployment)

## Description
The specifications of the assignment were:
- write a CRUD application for books (title and author fields) using Laravel
- allow book sort and filter
- allow csv and xml book export with the option to export all fields or title/author only.

Additional features were added to the project like:
- selection (by row, all in regard of the search parameters)
- bulk action using the selection (export and delete)
- pagination
- localization.

Please follow the next sections to use the project !

## Local Installation
To get started with this project, follow these steps:

1. Clone the repository:
   ```bash
   git clone https://github.com/qducros/yaraku-assignment.git yaraku-assignment
   ```

2. Navigate into the project directory:
   ```bash
   cd yaraku-assignment
   ```

3. Run docker compose:
   ```bash
   docker compose up -d server php mysql npm
   ```
   
4. Install composer dependencies:
   ```bash
   docker compose run --rm composer i
   ```

5. Set up environment variables (if applicable):
   Create a `.env` file based on `.env.example` and add the mysql configuration.
   ```bash
   DB_CONNECTION=mysql
   DB_HOST=yaraku-assignment-mysql
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=laravel
   DB_PASSWORD=secret
   DB_COLLATION=utf8mb4_unicode_ci
   ```
   
6. Generate the key:
   ```bash
   docker compose run --rm php sh -c "php artisan key:generate"
   ```

7. Cache the config and run the migration with or without seeding:
   ```bash
   docker compose run --rm php sh -c "php artisan config:cache"
   docker compose run --rm php sh -c "php artisan migrate"
   docker compose run --rm php sh -c "php artisan migrate --seed"
   ```

8. Open your browser and visit `http://localhost:8000`

## Production deployment
Using free tier railway service.

1. Connect using Github.

2. Create a new project using "Deploy from GitHub repo", paste the repository name and click on "Deploy Now" (it will fail because some configuration is missing).

3. Click on "New" to create a new Mysql Database. It will deploy automatically successfully.

4. In the Laravel Settings tab, several things to do:
- Add a Root Directory to override the default one:
  ```bash
  src
  ```
- Generate a new domain name in Public Networking and save it for later (it will look like *.up.railway.app).

5. Now we must configure the environment variables for our Laravel application using the raw editor from the Variables tab.
- Copy/paste the .env.example and add a new APP_KEY you can generate locally using:
   ```bash
   docker compose run --rm php sh -c "php artisan key:generate --show"
   ```
- Change APP_ENV and APP_DEBUG like below:
  ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```
- Select your Mysql database and go to the Variables tab. Copy the MYSQL_PUBLIC_URL which looks like mysql://db_user:db_pwd@db_host:db_port/db_name and use all these data as your laravel env variables:
   ```bash
   DB_CONNECTION=mysql
   DB_HOST=db_host
   DB_PORT=db_port
   DB_DATABASE=db_name
   DB_USERNAME=db_user
   DB_PASSWORD=db_pwd
   DB_COLLATION=utf8mb4_unicode_ci
   ```
- We will use the previously generated domain name for APP_URL and ASSET_URL:
  ```bash
  APP_URL=https://yaraku-assignment-production.up.railway.app
  ASSET_URL=https://yaraku-assignment-production.up.railway.app
  ```
- Finally we must override the build commands used by nixpacks which will be run during the deployment:
  ```bash
  NIXPACKS_BUILD_CMD=composer install && npm install --production && npm run build && php artisan optimize && php artisan config:cache && php artisan event:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --force
  ```

6. You can now deploy your laravel application a second time and once the deployment is over (it can take some time), visit `https://*.up.railway.app`.
