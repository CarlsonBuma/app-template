# Project Overview
Framework: Laravel 12, PHP 8.3
Docs: https://laravel.com/

# Stack Overview:
Key features, that enriches our backend.
  > DB: PSQL - pgvector/pgvector:latest 
    > implements Extension "vectors"
  > User Authentication
    > by Laravel Passport (Oauth 2.0)
  > User Access Management
    > Paddle Payment Gateway

## User Account Management
Basic user authentification via Laravel Passport and account access.
  - References: "\Controllers\User\Auth"
  - Docs: https://laravel.com/docs/12.x/passport
  - Ref: User Authentication

## Google Cloud Plattfrom
### Geolocation
Google Geolocation allows geolocating addresses within our app, provided by Client.
  - see "Providers\GeolocationProvider"

### Cloud Storage (GCS)
Google Cloud Storage allows storing images externally.
  - see "Providers\CloudStorageProvider"

## User Access Management
Manages client payments and interacts with our app backend (via  webhooks) to verify user access requests, according purchased prices.

### Stack Overview
  - Initial Client Access Request
    - See "Controllers\User\UserAccessController::initializeClientCheckout()"
  - Logic - User Access Handling by Prices
    - See "Controllers\Access"
  - Webhook
    - See "Listeners\PaddleWebhookListener"
    - Webhook Endpoint: "routes\web"
    - Middleware: "\Middleware\PaddleWebhookVerification"
  - APIs
    - see "Providers\PaddleProvider"

### Setup Paddle Webhook Access
  1. Go Paddle Developer Tools (https://sandbox-vendors.paddle.com/)
    1. APP Authentication: Define Paddle API Key 
      - Set API Key in .env-variables
    2. Paddle Notifications: Add new webhook destination
      - Set URL: { ASSET_URL } + /access/webhook
      - Select webhook events
      - Paste Webhook Secret Key in .env file
  2. Backend: Setup Access Logic
    1. Setup Access Handling - Adjust Access Logic, if necessary
      - Ref.: "Accesshandler::addUserAccess"
      - Dependencies: "Controllers\Access\"
    2. Release Price within "PaddlePriceHandler"
  3. Paddle: Create Product Price
    - Webhook will be sent to backend, which created/updates Prices
  4. App Admin Panel: Publish Price within App

### Local Webhook Testing: 
Install: Ngrok (Reverse Proxy)
  - Start: ngrok http http://127.0.0.1:8000
  - Check Webhooks: Ngrok Web Interface
