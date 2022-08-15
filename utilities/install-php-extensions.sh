#!/usr/bin/env bash

# Author : Wajdi Jurry <jurrywajdi@yahoo.com>
# All Rights Reserved

source ./utilities/progressbar.sh || exit 1

# Define array of php & pecl extensions
php_extensions=(intl gettext gd bcmath zip pdo_mysql opcache sockets)
pecl_extensions=(redis mongodb yaml)

# Install xdebug extension if in dev mode
if [ "$APP_ENV" == "local" ]; then
  pecl_extensions+=(xdebug)
fi


### DO NOT EDIT AREA ###
echo "Configuring pdo_mysql and gd extensions ..."
docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd > /dev/null
docker-php-ext-configure gd --with-freetype --with-jpeg > /dev/null
echo "Done configuring!"

echo "Installing php extensions ..."
php_extensions_count=${#php_extensions[@]}
i=0
draw_progress_bar $i ${php_extensions_count} "extensions"
for ext in ${php_extensions[*]}; do
  docker-php-ext-install ${ext} > /dev/null
  i=$((i+1))
  draw_progress_bar $i ${php_extensions_count} "extensions"
done
echo

echo "Installing PECL extensions ..."
pecl_extensions_count=${#pecl_extensions[@]}
i=0
draw_progress_bar $i ${pecl_extensions_count} "extensions"
for ext in ${pecl_extensions[*]}; do
  echo '' | pecl install ${ext} > /dev/null
  if [ "$ext" == "xdebug" ]; then
    echo "zend_extension=${ext}.so" > "/usr/local/etc/php/conf.d/${ext}.ini"
  else
    echo "extension=${ext}.so" > "/usr/local/etc/php/conf.d/${ext}.ini"
  fi
  i=$((i+1))
  draw_progress_bar $i ${pecl_extensions_count} "extensions"
done
echo
