#!/bin/bash
sudo apt-get -y update

# set up a silent install of MySQL
source yobichain.conf

db_root_pass=$1

export DEBIAN_FRONTEND=noninteractive
sudo sh -c "echo mysql-server-5.7 mysql-server/root_password password "$db_root_pass" | debconf-set-selections"
sudo sh -c "echo mysql-server-5.7 mysql-server/root_password_again password "$db_root_pass" | debconf-set-selections"


# install the LAMP stack
sudo apt-add-repository -y ppa:ondrej/php
sudo apt-get -y update

sudo apt-get -y install apache2
sudo apt-get -y install mysql-server
sudo apt-get -y install php7.0
sudo apt-get -y install php7.0-mysql

# restart Apache
sudo service apache2 restart
