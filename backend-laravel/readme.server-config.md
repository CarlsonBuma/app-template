# Server Confiration
Lifehacks via SSH Terminal

## Deployment
Exec deploy.sh file in root folder
   - cd var/www/vhosts/app-root-folder
   - chmod +x app-deploy.sh
   - ./app-deploy.sh

## Cron Scheduler
Automatically triggers actions at certain events.
Current Implementations:
   - Backup Database (see "routes\console.php")

### Setup
1. Create new cron file
   - sudo crontab -e
2. Edit File and add command
   - * * * * * cd /var/www/vhosts/root-folder && php artisan schedule:run >> /dev/null 2>&1
3. Check Scheduler
   - sudo crontab -l
   - sudo service cron status
   - sudo service cron start (if not running)

## SSH SET Global Variables
Install Packages:
   - /usr/bin
Globals
   - sudo ln -s $(which node) /usr/bin/node
   - sudo ln -s $(which npm) /usr/bin/npm
   - sudo ln -s /opt/plesk/php/8.3/bin/php /usr/bin/php

## Grant Server App Folder access
if CORS issues, its most probably cauz of missing ownership
   - sudo chmod -R 775 USER
   - sudo chown -R USER:root USER
   - sudo chown -R USER:psacln /var/www/vhosts/path-to-folder
