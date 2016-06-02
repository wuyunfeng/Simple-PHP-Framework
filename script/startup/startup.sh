#!/bin/bash
sudo nginx
sudo php-fpm
mysql.server start
redis-server /usr/local/etc/redis.conf
