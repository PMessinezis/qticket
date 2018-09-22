pushd qticket
php artisan cache:clear
php artisan config:clear
php artisan config:cache
php composer.phar dumpautoload
php artisan queue:work  --sleep=5 -vvv --tries=2