#!/bin/bash

# hamlib API
# 20230717: initial version by Arne, DL4OCE   ..._._
# 

mkdir -p /tmp/hamlib
#wget https://github.com/Hamlib/Hamlib/releases/download/4.5.5/hamlib-4.5.5.tar.gz -o /tmp/hamlib/hamlib-4.5.5.tar.gz
git clone https://github.com/Hamlib/Hamlib.git /tmp/hamlib/
cd /tmp/hamlib
./bootstrap
./configure
make -j4
make install
ldconfig
mkdir /var/www/html/hamlib_api/
git clone https://github.com/DL4OCE/hamlib_api_dl4oce.git /var/www/html/hamlib_api/
a2enmod rewrite
echo "<Directory /var/www/html/hamlib_api>
        Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All
        Require all granted
</Directory>" > /etc/apache2/sites-available/hamlib_api.conf
ln -s /etc/apache2/sites-available/hamlib_api.conf /etc/apache2/sites-enabled/hamlib_api.conf
addgroup www-data dialout
systemctl restart apache2
