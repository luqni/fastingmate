#!/bin/bash
set -e

echo "🚀 Starting FastingMate Application..."

# Check if we can connect to the database
echo "🔍 Checking database connection..."
# We use 'php artisan model:show' on a simple model (User) as a crude connection check, 
# or just attempt migrate:status which is safer.
php artisan migrate:status > /dev/null 2>&1 || {
    echo "⚠️  Database connection failed or migrations table missing."
}

echo "📦 Running Migrations (Safe Mode)..."
# --force is required for production
# This command automatically checks the 'migrations' table and only runs pending migrations.
php artisan migrate --force

echo "🧹 Clearing Caches..."
php artisan optimize:clear

echo "✅ App Ready! Starting Server..."
# Execute the passed command (CMD from Dockerfile)
exec "$@"
