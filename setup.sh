#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

echo "Creating storage/oauth directory..."
mkdir -p storage/oauth

echo "Generating private key..."
openssl genrsa -out storage/oauth/private.key 2048

echo "Generating public key..."
openssl rsa -in storage/oauth/private.key -pubout -out storage/oauth/public.key

echo "Setting permissions..."
chmod 600 storage/oauth/private.key
chmod 644 storage/oauth/public.key

echo "Running migrations..."
vendor/bin/phinx migrate

echo "Seeding users..."
vendor/bin/phinx seed:run -s UserSeeder

echo "Seeding OAuth clients..."
vendor/bin/phinx seed:run -s OAuthClientSeeder

echo "Setup complete!"