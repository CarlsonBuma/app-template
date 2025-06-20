# Webapp Template
by Carlson, 2025-06-25

## Folder Structure
Root folder:
   - /backend (see Readme.md)
   - /docs
   - /frontend (see Readme.md)
   - Start Environment - "start in terminal":
      > "./env-start.ps1"
   - Setup Docker Environment - "docker compose up":
      > "./docker-compose.yml"

## Security Check
   - go "/frontend": 
      - npm update
      - npm audit
      - npm audit fix --force
   - go "/backend": 
      - composer outdated
      - composer update
      
### SSH
console: ssh-keygen

# System Dependencies
Root:
- Backend
   > Laravel 11, PHP 8.3
   > DB: PSQL - pgvector/pgvector:latest 
      > implements Extension "vectors"
   > User Authentication
      > by Laravel Passport (Oauth 2.0)
      > see "\Controllers\Auth\"
   > User Access Management
      > Paddle Payment Gateway
      > see "\Controllers\Access\"
- Frontend 
   > Quasar CLI v.2.17 ("$quasar info") 
      > VueJS Framework
   > User Authentication
      > see "\src\pages\auth\"
   > User Access Management
      > see "\src\pages\access\"
      > Note: Client Checkout (by PaddleJS) as initial user-access-request
         > User-access-request must be verified via our backend webhooks
         > see: "User Access Management"
   > Cookie Consent (cookieConsent.js)
      > https://github.com/eyecatchup/vue-cookieconsent
      > see "\boot\cookieConsent"
   > Google API - Geolocation
      > https://developers.google.com/maps/documentation/geocoding/start
      > see "\components\Google..."

# Local System Requirements
## Setup Database
Database (Psql/Vector) runs in a Docker Container
  - Setup: see "/docker-compose.yml"
  - Initiate: "docker-compose up -d"

## Local Backend Compiler (PHP)
Instead of Docker, use a local Backend Compiler
   - Install newest PHP Compiler
   - Setup Environment Variables "Path/to/php.exe"
   - Setup php.ini (Development) file by
      > Set Variables
      > Set Extensions
   - PHP - Xdebug
      > Download php_xdebug
      > Adjust php.ini file
         zend_extension="B:\PHP-Composer\php-8.3.9\ext\php_xdebug.dll"
         xdebug.mode=debug
         xdebug.start_with_request=yes
         xdebug.client_port=9003
         xdebug.client_host=127.0.0.1
         xdebug.connect_timeout_ms=200


# Go Live - Server Configurations
Backend (Laravel12) & Frontend (Node.js)
Ref. readme.ssh.md
