@echo off
echo Starting Laravel Octane with RoadRunner...
echo.
echo Available ports: 8000, 8001, 8002, 8003, 8004, 8005
echo.
set /p port="Enter port number (default 8000): "
if "%port%"=="" set port=8000
echo.
echo Starting Octane server on port %port%...
php artisan octane:start-windows --host=127.0.0.1 --port=%port% --workers=1
pause
