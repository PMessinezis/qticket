@echo off
SETLOCAL ENABLEEXTENSIONS ENABLEDELAYEDEXPANSION
chcp 1253
pushd %~dp0
:BigCycle
set /A C=0
del storage\Logs\Cycle.LOG   
call :reload  2>&1  > storage\Logs\Cycle.LOG   
call :Cycle   2>&1   >> storage\Logs\Cycle.LOG   
goto :BigCycle
goto end

:Cycle
set OldDate=%date%
ECHO %date% %time% - %C% 
if %C% GTR 10 (
	Call :reload
	set /A C=0
)
php artisan queue:work  -vvv --tries=15 --stop-when-empty
sleep 30
set /A C=%C%+1
if "%OldDate%" NEQ "%date%" goto :end
goto :cycle



:reload
php artisan cache:clear
php artisan config:clear
php artisan config:cache
goto end

:end
