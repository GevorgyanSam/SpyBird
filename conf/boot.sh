#!/bin/bash

apt-get update && apt-get upgrade -y && apt-get autoremove -y && apt-get autoclean

apt-get install -y software-properties-common
add-apt-repository ppa:ondrej/php

apt-get install -y composer php php-curl php-xml php-mysql php-imagick php-gd nodejs npm

cd /var/www/SpyBird
composer install
npm install
php artisan storage:link
npm run production
# php artisan migrate

service apache2 start

cp /var/www/SpyBird/conf/spybird.conf /etc/apache2/sites-available/
cd /etc/apache2/sites-available/
a2dissite 000-default.conf
a2ensite spybird.conf
service apache2 reload
a2enmod rewrite
service apache2 restart

apt-get autoremove -y && apt-get autoclean
