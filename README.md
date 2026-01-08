# athletix

-- open the readme in the vs code thanks

1. install XAMPP/WAMPP/LARAGON or any host server for database (I used XAMPP)
2. install composer [download composer heere(Composer-Setup.exe)](https://getcomposer.org/download/)
3. setup your .env(if not exist create .env(C:\xampp\htdocs\athletix\.env))
  # dot env file 
  APP_NAME=Athletix
APP_ENV=local
APP_KEY=base64:dPtH6Q2J4e2DqSjQe6x7keFF1D+XBxnY/OYdlrW3OoM=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=athletix
DB_USERNAME=root
DB_PASSWORD=  

# default username: admin.athletix.ph@ctu.com
# default pass: Admin@Athletix


SESSION_DRIVER=file
QUEUE_CONNECTION=sync

SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=file
# CACHE_PREFIX=



CACHE_DRIVER=file

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreplyathletix@gmail.com
MAIL_PASSWORD=vxfbnacnmnsrxadt  # Ensure this is your correct App Password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreplyathletix@gmail.com
MAIL_FROM_NAME="${APP_NAME}"



AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

4. set up your database admin([create new and name it athletix](http://localhost/phpmyadmin/index.php))
5. ran php artisan migrate:fresh --seed
6. install  php artisan storage:link
7. install composer require barryvdh/laravel-dompdf
8. install composer require maatwebsite/excel (don't mind the error)
9. uncomment the extesion go to this if you were using xamoo(C:\xampp\php\php.ini) and open via notepad or vs code then remove the ; before the extension=gd (note ctrl + f to find the extension)
10. ran php artisan serve
