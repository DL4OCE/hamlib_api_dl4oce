#!/bin/bash

mkdir -p /tmp/hamlib
#wget https://github.com/Hamlib/Hamlib/releases/download/4.5.5/hamlib-4.5.5.tar.gz -o /tmp/hamlib/hamlib-4.5.5.tar.gz
git clone https://github.com/Hamlib/Hamlib.git /tmp/hamlib/
cd /tmp/hamlib
./bootstrap
./configure
make -j4
make install
ldconfig
https://github.com/DL4OCE/rigctl_api.git -o /var/www/html/rigctl_api
a2enmod rewrite
systemctl restart apache2
