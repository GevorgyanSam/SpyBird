#!/bin/sh

# Export .env variables
set -a
. ./.env
set +a

# Make sure directory exists
mkdir -p /etc/nginx/sites-available
mkdir -p /etc/nginx/sites-enabled/

# Generate nginx configuration using envsubst and proxy.template
envsubst '${PREFIX} ${APP_PORT} ${PMA_PORT} ${MAILPIT_PORT_WEB}' < ./proxy.template > /etc/nginx/sites-available/$PREFIX.conf

# Configure nginx
ln -s /etc/nginx/sites-available/$PREFIX.conf /etc/nginx/sites-enabled/
systemctl restart nginx
systemctl reload nginx

# Update /etc/hosts file
echo "" >> /etc/hosts
echo -e "127.0.0.1\tapp.${PREFIX}.local" >> /etc/hosts
echo -e "127.0.0.1\tdb.${PREFIX}.local" >> /etc/hosts
echo -e "127.0.0.1\tmail.${PREFIX}.local" >> /etc/hosts
