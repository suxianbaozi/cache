1.建立新的域名 指向app-web

#nginx:

```
server {
        listen       80;
        server_name cache.qiushi.dev.reco.cn;
        index index.html index.htm index.php;
        root  /Users/www/cache/app-web;
        rewrite . /index.php;
        location ~ .*\.(php|php5)?$ {
            fastcgi_pass   unix:/tmp/php-cgi.sock;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME   $document_root$fastcgi_script_name;
            include        fastcgi_params;
        }
}
```

#apache:
apache需要打开rewrite模块

```
<VirtualHost *:80>
    DocumentRoot /var/www/music/app-web
    ServerName cache.qiushi.dev.reco.cn
    <Location />
        AddDefaultCharset utf-8
        RewriteEngine on
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . index.php [L,QSA]
    </Location>
</VirtualHost>
```
2.php安装memcache扩展

3.入口controller
app-web/controller/web/index.php

4.尝试访问
domain.com/?action=get_one
domain.com/?action=get_list
domain.com/?action=update



5.数据库和memcache配置文件
config/common.php
config/database.php


6.最好用自己数据库和memcache

7.数据库的话
reco_music->album->{album_id,desc}
