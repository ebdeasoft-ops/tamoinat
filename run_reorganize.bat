@echo off
chcp 65001 >nul
cd /d "%~dp0"
set "PHP=C:\xampp\php\php.exe"
if not exist "%PHP%" set "PHP=php"
set "LOG=reorganize_log.txt"

echo ===== %date% %time% ===== > "%LOG%"
echo [1] Backup to _backup_before_reorg >> "%LOG%"
robocopy resources\views _backup_before_reorg\resources\views /E /NFL /NDL /NJH /NJS /NP >> "%LOG%"
robocopy app _backup_before_reorg\app /E /NFL /NDL /NJH /NJS /NP >> "%LOG%"
robocopy routes _backup_before_reorg\routes /E /NFL /NDL /NJH /NJS /NP >> "%LOG%"
robocopy config _backup_before_reorg\config /E /NFL /NDL /NJH /NJS /NP >> "%LOG%"

echo [2] Reorganize >> "%LOG%"
"%PHP%" reorganize_views.php --apply >> "%LOG%" 2>&1
echo EXIT=%ERRORLEVEL% >> "%LOG%"

echo [3] view:clear >> "%LOG%"
"%PHP%" artisan view:clear >> "%LOG%" 2>&1
"%PHP%" artisan route:clear >> "%LOG%" 2>&1

echo ===== DONE ===== >> "%LOG%"
type "%LOG%"
pause
