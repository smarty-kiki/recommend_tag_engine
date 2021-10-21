#!/bin/bash

ROOT_DIR="$(cd "$(dirname $0)" && pwd)"/../../..

ln -fs $ROOT_DIR/project/config/production/nginx/recommend_tag_engine.conf /etc/nginx/sites-enabled/recommend_tag_engine
/usr/sbin/service nginx reload

/bin/bash $ROOT_DIR/project/tool/dep_build.sh link
/usr/bin/php $ROOT_DIR/public/cli.php migrate:install
/usr/bin/php $ROOT_DIR/public/cli.php migrate

ln -fs $ROOT_DIR/project/config/production/supervisor/recommend_tag_engine_queue_worker.conf /etc/supervisor/conf.d/recommend_tag_engine_queue_worker.conf
/usr/bin/supervisorctl update
/usr/bin/supervisorctl restart recommend_tag_engine_queue_worker:*
