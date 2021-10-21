#!/bin/bash

ROOT_DIR="$(cd "$(dirname $0)" && pwd)"/../../..

date > /tmp/php_exception.log
date > /tmp/php_notice.log
date > /tmp/php_module.log
chown www-data:www-data /tmp/php_exception.log
chown www-data:www-data /tmp/php_notice.log
chown www-data:www-data /tmp/php_module.log

echo ". $ROOT_DIR/project/config/development/bash/cli_complete.bash" >> ~/.bashrc
