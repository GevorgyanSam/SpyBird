#!/bin/bash

# Change directory to the app path
DIRECTORY="$1"
shift
cd "$DIRECTORY/docker" || exit 1

# Check if any arguments are provided
if [ $# -eq 0 ]; then
    docker compose exec -u spybird app bash
    exit 1
fi

# Prepend 'docker compose exec app' to the provided command
PREFIX="docker compose exec -u spybird app"

# Execute the command
$PREFIX "$@"
