@echo off
cd /d "%~dp0"
echo Running database migrations...
"C:\xampp\php\php.exe" artisan migrate --force
echo.
pause
