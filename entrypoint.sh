#!/bin/sh

#启动apache
service apache2 start

#启动crontab
cron

#启动supervisor
/usr/local/bin/supervisord -c /var/www/shopnc/node/chat_supervisord.conf
