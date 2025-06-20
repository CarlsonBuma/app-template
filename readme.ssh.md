## Deployment
Go App Root Folder:
   - cd var/www/vhosts/gigup.ch/app-events
   - chmod +x app-deploy.sh
   - ./app-deploy.sh

## Setup Cron Scheduler
1. Create new cron file
   - sudo crontab -e
2. Edit File and add command
   - * * * * * cd /var/www/vhosts/gigup.ch/app-events/backend && php artisan schedule:run >> /dev/null 2>&1
3. Check Scheduler
   - sudo crontab -l
   - sudo service cron status
   - sudo service cron start (if not running)

## Shortcuts
Shortcuts:
   - Paste: Shift + CTRL + V
   - Copy: 

## SSH Globals
Install new Packages:
   - /usr/bin
Globals
   - sudo ln -s $(which node) /usr/bin/node
   - sudo ln -s $(which npm) /usr/bin/npm
   - sudo ln -s /opt/plesk/php/8.3/bin/php /usr/bin/php

## Grant Server App Folder access
   - sudo chmod -R 775 Localbeat
   - sudo chown -R root:root Localbeat

## Lifehack
if CORS issues, its most probably cauz of missing ownership

Oauth
   - sudo chown -R gigup:psacln /var/www/vhosts/gigup.ch/app-events/backend/storage
   - sudo chown -R gigup:psacln /var/www/vhosts/gigup.ch/app-events/backend/bootstrap/cache

Public Storage
   - sudo chown -R gigup:psacln /var/www/vhosts/gigup.ch/app-events/backend/public/storage
   - sudo chmod -R 775 /var/www/vhosts/gigup.ch/app-events/backend/public/storage
