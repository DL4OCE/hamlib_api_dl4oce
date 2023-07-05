# rigctl_api
API to enable usage of hamlib functionality through a web REST API

Have a look at prereqs/install.sh for setup!

Preparation:
a2enmod rewrite

/etc/apache2/apache2.conf:
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All
#None
        Require all granted
</Directory>

