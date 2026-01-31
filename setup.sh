#!/bin/bash

echo "========================================="
echo "iProject Laravel - Quick Setup Script"
echo "========================================="
echo ""

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    echo "Visit: https://getcomposer.org/download/"
    exit 1
fi

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "❌ NPM is not installed. Please install Node.js and NPM first."
    echo "Visit: https://nodejs.org/"
    exit 1
fi

echo "✅ Prerequisites check passed"
echo ""

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install

# Install JavaScript dependencies
echo "📦 Installing JavaScript dependencies..."
npm install

# Copy .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "✅ .env file created"
else
    echo "ℹ️  .env file already exists"
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

echo ""
echo "========================================="
echo "Setup Complete!"
echo "========================================="
echo ""
echo "Next steps:"
echo "1. Edit .env file and configure your database"
echo "2. Create database: CREATE DATABASE iproject_laravel;"
echo "3. Run migrations: php artisan migrate"
echo "4. Seed database: php artisan db:seed"
echo "5. Create storage link: php artisan storage:link"
echo "6. Compile assets: npm run dev"
echo "7. Start server: php artisan serve"
echo ""
echo "Visit: http://localhost:8000"
echo "Default admin: admin@iproject.local / password"
echo ""
