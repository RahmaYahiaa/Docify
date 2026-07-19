#!/bin/bash

echo "Starting Laravel Octane with RoadRunner..."
echo ""
echo "Available ports: 8000, 8001, 8002, 8003, 8004, 8005"
echo ""
read -p "Enter port number (default 8000): " port
port=${port:-8000}
echo ""
echo "Starting Octane server on port $port..."
php artisan octane:start-windows --host=127.0.0.1 --port=$port --workers=1
