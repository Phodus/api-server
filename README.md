# api-server

## Installation

```
# cd /var/www
# git clone https://github.com/Phodus/api-server.git
# cd api-server
# composer install
```


Create a vhost on Apache2
```
# nano /etc/apache2/sites-enabled/api.mydomain.com.conf
```

```
<VirtualHost *:80>
    ServerName api.mydomain.com
    SetEnv APPLICATION_ENV "dev"

    DocumentRoot /var/www/api-server/public
    <Directory "/var/www/api-server/public">
        AllowOverride None
        Options -MultiViews
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ index.php [QSA,L]
    </Directory>

    DirectoryIndex index.php index.htm index.html
    AddDefaultCharset UTF-8
    ErrorLog /var/www/api-server/log/error.log
</VirtualHost>
```

Restart Apache2
```
# /etc/init.d/apache2 restart
```

Edit your hosts file
```
# sudo nano /etc/hosts
```

```
192.168.X.X     api.mydomain.com
```

Create a new config file per environment
```
# cd /var/www/api-server/app/config
# cp config.env.ini config.dev.ini 
```

Go to this url under your web browser: http://api.mydomain.com/


## Todo
- Add login with Authorization Token
- Add translation
- Use Php code checker
