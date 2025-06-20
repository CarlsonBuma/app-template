# Project Overview
Framework: Laravel 12
Docs: https://laravel.com/

## Initialization
  - composer install / update
    > php artisan --version
    > composer clear cache
    > composer show "pgvector/pgvector" --alL
  - Setup meta-data in package.json

### DB Migrate Data & Oauth
  - php artisan key:generate
  - php artisan migrate
  - php artisan passport:keys
    > Add Oauth-keys to "keys/passport"
  - php artisan passport:client --personal
    > ClientUser Authentication Token

### DB Seeding
  - php artisan db:seed --class=UserSeeder
  - other classes

### Storage Setup
  - php artisan storage:link

### Setup Mail Driver
  - Choose your Mail Driver (according Serverhost)
  - Enter Attributes into .env file

# Feature Enhacements
Key features, that enriches our backend.

## App Geolocation
Google Geolocation allows geolocating addresses within our app, provided by Client.
  - see "\Models\AppGeolocations\"

## User Account Management
Basic user authentification via Laravel Passport and account access.
  - References: "\Controllers\User\Auth"
  - Docs: https://laravel.com/docs/12.x/passport

## User Access Management
Manages client payments and interacts with our app backend (via  webhooks) to verify user access requests, according purchased prices.

Collection: "\Collections\AccessCollection"
Logic: "\Controllers\Access\"
Listeners: "\Listeners\PaddleWebhookListener"
Webhook Endpoint: "\routes\web"
App Access Management
  - App Access Definition: "\Controllers\Access\PaddlePriceHandler"
  - Manage User Access: "\Controllers\Access\UserAccessController"
  - Handle User Access: "\Controllers\Access\AccessHandler"

### Setup Paddle Webhooks
Go Paddle Developer Tools (https://sandbox-vendors.paddle.com/)
  1. Authentication: 
    - Set API Key in .env-variables
  2. Notifications: Add new webhook destination
    - Set URL: { ASSET_URL } + /access/webhook
    - Select webhook events
    - Paste Webhook Secret Key in .env file

### Set/Adjust Access Logic
  1. Define Price according "PaddlePriceHandler"
  2. App Admin Panel: Publish Price
  3. Set User Access according "AccessHandler::addUserAccess"
  4. Adjust "AccessHandler", if necessary

### Local Webhook Testing: 
Install: Ngrok (Reverse Proxy)
  - Start: ngrok http http://127.0.0.1:8000
  - Check Webhooks: Ngrok Web Interface
