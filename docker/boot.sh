#!/bin/bash

# Read local path from the .env file
ENV_VAR_NAME="LOCAL_PATH"

# Check if .env file exists
if [ -f .env ]; then
    # Get the value of LOCAL_PATH from .env
    LOCAL_PATH=$(grep -w "$ENV_VAR_NAME" .env | cut -d '=' -f2)

    # Check if LOCAL_PATH is empty
    if [ -z "$LOCAL_PATH" ]; then
        echo "Error: $ENV_VAR_NAME is not defined in the .env file."
        exit 1
    fi
else
    # Error message if .env file not found
    echo "Error: .env file not found."
    exit 1
fi

# Change directory to LOCAL_PATH, exit if fails
cd "$LOCAL_PATH/docker" || exit 1

# Add an alias to ~/.bashrc
echo "alias spybird='bash $LOCAL_PATH/docker/spybird.sh $LOCAL_PATH'" >> ~/.bashrc

# Prepend 'docker compose exec app' to execute commands
PREFIX="docker compose exec app"

# Install PHP dependencies using Composer
$PREFIX composer install

# Install JavaScript dependencies using npm
$PREFIX npm install

# Run database migrations
$PREFIX php artisan migrate

# Create a symbolic link to storage
$PREFIX php artisan storage:link

# Build production assets using npm
$PREFIX npm run production
