#!/bin/bash
php -r 'exit(extension_loaded("mongo") ? 0 : 1);' && exit 0
pecl install mongo

