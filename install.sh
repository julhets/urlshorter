#!/bin/bash

echo "Starting installation..."

# Git
echo "Installing Git"
apt-get install git -y > /dev/null

# PHP
echo "Updating PHP repository"
apt-get install python-software-properties build-essential -y > /dev/null --force-yes
add-apt-repository ppa:ondrej/php5 -y > /dev/null --force-yes
apt-get update > /dev/null

echo "Installing PHP"
apt-get install php5-common php5-dev php5-cli php5-fpm -y > /dev/null --force-yes

echo "Installing PHP extensions"
apt-get install curl php5-curl php5-gd php5-mcrypt php5-mysql php-apc -y > /dev/null --force-yes

# MySQL
#echo "Preparing MySQL"
#apt-get install debconf-utils -y > /dev/null --force-yes
#debconf-set-selections <<< "mysql-server mysql-server/root_password password abc123"
#debconf-set-selections <<< "mysql-server mysql-server/root_password_again password abc123"

#echo "Installing MySQL"
#apt-get install mysql-server -y > /dev/null --force-yes

#echo "Creating database"
#MyUSER="root"
#MyPASS="abc123"
#HostName="127.0.0.1"
#dbName="urlshorter"

#mysql -u $MyUSER -p$MyPASS -Bse "CREATE DATABASE $dbName;"

#echo "Installing Composer"
#curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

#echo "Downloading application"
#cd
#mkdir appdesafio
#cd appdesafio
#git clone https://github.com/julhets/urlshorter.git

#echo "Installing bundles with Composer"
#cd ~/appdesafio/urlshorter
#composer install

#echo "Creating DB schema"
#cd ~/appdesafio/urlshorter
#vendor/bin/doctrine orm:schema-tool:create

echo "Finished install."