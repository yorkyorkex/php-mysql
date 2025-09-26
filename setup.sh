#!/bin/bash

# Course Management System Setup Script
echo "==================================="
echo "Course Management System Setup"
echo "==================================="

# Check if MySQL is installed
if ! command -v mysql &> /dev/null; then
    echo "❌ MySQL is not installed. Please install MySQL first."
    exit 1
fi

echo "✅ MySQL is installed"

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP first."
    exit 1
fi

echo "✅ PHP is installed"

# Database setup
echo ""
echo "📊 Setting up database..."
echo "Please enter your MySQL credentials:"

read -p "MySQL username: " DB_USER
read -s -p "MySQL password: " DB_PASS
echo ""

# Create database and import schema
mysql -u "$DB_USER" -p"$DB_PASS" -e "CREATE DATABASE IF NOT EXISTS course_management;" 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Database created successfully"
else
    echo "❌ Failed to create database. Please check your credentials."
    exit 1
fi

# Import schema
mysql -u "$DB_USER" -p"$DB_PASS" course_management < database/schema.sql

if [ $? -eq 0 ]; then
    echo "✅ Database schema imported successfully"
else
    echo "❌ Failed to import database schema"
    exit 1
fi

# Import sample data
mysql -u "$DB_USER" -p"$DB_PASS" course_management < database/sample_data.sql

if [ $? -eq 0 ]; then
    echo "✅ Sample data imported successfully"
else
    echo "❌ Failed to import sample data"
    exit 1
fi

# Update database configuration
echo ""
echo "⚙️  Updating database configuration..."

# Create a backup of the original config
cp api/config/database.php api/config/database.php.backup

# Update the database configuration file
sed -i "s/private \$username = 'root';/private \$username = '$DB_USER';/g" api/config/database.php
sed -i "s/private \$password = '';/private \$password = '$DB_PASS';/g" api/config/database.php

echo "✅ Database configuration updated"

echo ""
echo "🚀 Setup completed successfully!"
echo ""
echo "To start the application:"
echo "1. Run: php -S localhost:8000"
echo "2. Open your browser and go to: http://localhost:8000"
echo ""
echo "Or place the files in your web server's document root."
echo ""
echo "==================================="