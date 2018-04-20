# Installation

    sudo rm -rf /var/www/html/thunder
    wget https://ftp.drupal.org/files/projects/thunder-8.x-2.15-core.tar.gz
    tar xvzf thunder-8.x-2.15-core.tar.gz
    sudo mv thunder-8.x-2.15 thunder
    cd thunder
    composer install
    cd ..
    sudo mv thunder /var/www/html/
    sudo chown apache:apache /var/www/html/thunder -R


## Mysql Setup

    mysql -u root -p'!dc_young_devtests#+' -e "DROP DATABASE IF EXISTS thunderdb";
    mysql -u root -p'!dc_young_devtests#+' -e "CREATE DATABASE thunderdb CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
    mysql -u root -p'!dc_young_devtests#+' -e "CREATE USER thunder@localhost IDENTIFIED BY '12345abcd'"
    mysql -u root -p'!dc_young_devtests#+' -e "GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES ON thunderdb.* TO 'thunder'@'localhost' IDENTIFIED BY '12345abcd'"


## Apache Configuration

Necessary if thunder is not stored in DocumentRoot

    sudo nano /etc/httpd/conf/httpd.conf

Add alias to apache config (/var/www/thunder/ replace with thunder path)

    Alias /thunder/ "/var/www/thunder/"
    <Directory "/var/www/thunder/">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

Add permissions

    # Mac --> sudo chown _www:_www /var/www/thunder -R
    sudo chown apache:apache /var/www/thunder -R
    sudo chmod 0777 /var/www/thunder

Restart Apache

    sudo service httpd restart

Add to .htaccess (thunder is alias name)

    RewriteBase /thunder

Not sure why I changed that:

    <IfModule dir_module>
        DirectoryIndex index.html index.php
    </IfModule>


## Thunder Setup

Install and enable modules
* REST UI for config: https://www.drupal.org/project/restui
* File entity needed for uploading images/data: https://www.drupal.org/project/file_entity

Goto `Configuration >  Web services > REST` and enable Methods: `GET, POST, DELETE, PATCH`, Accepted request formats: `hal_json, json` and Authentication providers: `basic_auth, cookie` for: `Content, Paragraph, File, Media`


## Stuff

Json Api: https://www.drupal.org/project/jsonapi

composer config repositories.repo-name vcs https://github.com/BurdaMagazinOrg/module-dcx-integration.git

    cd /var/www/html/
    composer require drush/drush

https://www.drupal.org/project/file_entity
