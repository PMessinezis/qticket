@echo off
SETLOCAL ENABLEEXTENSIONS ENABLEDELAYEDEXPANSION
chcp 1253
pushd %~dp0
php artisan SendTestEmail 
popd
