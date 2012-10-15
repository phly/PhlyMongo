#!/bin/bash
php -m | grep -vq mongo && exit 0
pecl install mongo
echo "extension=mongo.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
