#!/bin/bash

composer install
npm install
php artisan migrate
php artisan storage:link
npm run production
chmod -R 777 /var/www/SpyBird
