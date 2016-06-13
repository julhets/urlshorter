#Urlshorter API

##A REST API for Urlshorter Services.

* Pre requisites:
UPDATE SYSTEM
sudo apt-get update
MYSQL
sudo apt-get install mysql-server
PHP-APC
sudo apt-get install php-apc
CURL
sudo apt-get install curl
PHP-CLI
sudo apt-get php5-cli
GIT
sudo apt-get git
Composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

* Creating schema
vendor/bin/doctrine orm:schema-tool:create

* Starting server
sudo php -S localhost:8999 -t public/