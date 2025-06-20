<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

/**
 * Database backup scheduler
 *  > list scheduler: php artisan schedule:list
 *  > run scheduler: php artisan schedule:run
 *  > simulate scheduler: php artisan schedule:work
 * 
 * Requirements:
 *  > Ubuntu: Set Cron Taskscheduler
 *      - cron --version
 *      - sudo service cron status
 *  > pg_dump must be global available 
 *      - pg_dump --version
 *  
 */
if(config('database.backup_scheduler')) {
    Schedule::exec('php artisan backup:clean')
        ->dailyAt('09:50')
        ->before(function () {
            Log::channel('backup')->info('[Backup] Starting backup:clean at ' . now());
        })
        ->after(function () {
            Log::channel('backup')->info('[Backup] Completed backup:clean at ' . now());
        });

    Schedule::exec('php artisan backup:run --only-to-disk=backup_full_db')
        ->everyTwoHours()
        ->before(function () {
            Log::channel('backup')->info('[Backup] Starting full backup at ' . now());
        })
        ->after(function () {
            Log::channel('backup')->info('[Backup] Completed full backup at ' . now());
        });

    Schedule::exec('php artisan backup:run --only-db --only-to-disk=backup_only_db')
        ->everySixHours()
        ->before(function () {
            Log::channel('backup')->info('[Backup] Starting incremental backup:run at ' . now());
        })
        ->after(function () {
            Log::channel('backup')->info('[Backup] Completed incremental backup:run at ' . now());
        });
}
