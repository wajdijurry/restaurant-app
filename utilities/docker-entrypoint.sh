#!/usr/bin/env bash

# Author : Wajdi Jurry <jurrywajdi@yahoo.com>
# All Rights Reserved

# Copy PHP extensions INI files
cp -a php_extensions/. /usr/local/etc/php/conf.d/.

# Flush all cache
php artisan optimize:clear

# Execute migrations
php artisan migrate --no-interaction

exec "$@"
