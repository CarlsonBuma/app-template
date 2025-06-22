# Backend Initialization
Initialize Backend within Local Environment or Production.

## Install Dependencies
  - composer install / update
    > php artisan --version
    > composer clear cache
    > composer show "pgvector/pgvector" --alL
  - Setup meta-data in package.json

## DB Migrate Data & Oauth
  - php artisan key:generate
  - php artisan migrate
  - php artisan passport:keys
    > Add Oauth-keys to "keys/passport"
  - php artisan passport:client --personal
    > ClientUser Authentication Token

## DB Seeding
  - php artisan db:seed --class=UserSeeder
  - other classes

### Storage Setup
  - php artisan storage:link

### Setup Mail Driver
  - Choose your Mail Driver (according Serverhost)
  - Enter Attributes into .env file
