# Project Overview
## Setup: Laravel 12 - Framework
Website: https://laravel.com/

## Initialization
  - composer install / update
    > php artisan --version
    > composer clear cache
    > composer show "pgvector/pgvector" --alL
  - Setup meta-data in package.json

### DB Migrate Data
  - php artisan key:generate
  - php artisan migrate
  - php artisan passport:install
  - Enter Client Secrets in .env File

### DB Seeding
  - php artisan db:seed --class=UserSeeder

### Storage Setup
  - add folder 'storage/app/*public'
  - php artisan storage:link

### Setup Mail Driver
  - Choose your Mail Driver (according Serverhost)
  - Enter Attributes into .env file

 ## Live Deployment
 See Laravel 11 Docs & it's Dependencies
  - .env set to production
  - php artisan vendor:publish --tag=passport-config
    - Enter Client Secrets in .env File
  - composer install --optimize-autoloader --no-dev
  - php artisan config:cache
  - php artisan event:cache
  - php artisan route:cache


# Feature enhacements
Key features, that enriches our backend.

## User Account Management
Basic user authentification via Laravel Passport and account access.
    - See "\Controllers\Auth"

## User Access Management
Manages client payments and interacts with our app backend (via  webhooks) to verify user access requests, according purchased prices.
   1. Webhook testing: Install Ngrok (Reverse Proxy)
      - ngrok http http://127.0.0.1:8000
      - Check Webhooks: Ngrok Web Interface
   2. Set Paddle Developer Tools
      1. Paddle Cockpit Authentication: Set .env-variables
      2. Paddle Cockpit Notifications: Add new webhook destination
        - Set URL: { ASSET_URL } + /access/webhook
          - see "\routes\web"
        - Select webhook events
        - Paste Webhook Secret Key in .env file
   3. Define app logic according price-access
      - Adjust logic of price access (if neccessary)
      - Example: "\Controllers\Access\AccessHandler::$tokenCockpit"
   4. Initiate new Price Access 
      - see "\Controllers\Access\PaddlePriceHandler"

## App Geolocation
Google Geolocation allows geolocating addresses within our app, provided by Client.
  - see "\Models\AppGeolocations\"
