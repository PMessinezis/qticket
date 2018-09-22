pushd c:\xampp\qticket
c:\xampp\php\php artisan cache:clear
c:\xampp\php\php artisan config:clear
c:\xampp\php\php artisan config:cache
c:\xampp\php\php C:\Users\u96484\AppData\Roaming\composer\composer.phar dumpautoload
c:\xampp\php\php artisan queue:work  --sleep=5 -vvv --tries=2