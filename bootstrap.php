<?php

ini_set('display_errors', 'on');
date_default_timezone_set('Asia/Shanghai');

define('ROOT_DIR', __DIR__);
define('FRAME_DIR', ROOT_DIR.'/frame');
define('COMMAND_DIR', ROOT_DIR.'/command');
define('CONTROLLER_DIR', ROOT_DIR.'/controller');
define('UTIL_DIR', ROOT_DIR.'/util');
define('QUEUE_JOB_DIR', COMMAND_DIR.'/queue_job');

include FRAME_DIR.'/function.php';
include FRAME_DIR.'/otherwise.php';
include FRAME_DIR.'/storage/mongodb.php';
include FRAME_DIR.'/cache/redis.php';
include FRAME_DIR.'/queue/beanstalk.php';
include FRAME_DIR.'/log/file.php';

config_dir(ROOT_DIR.'/config');

include UTIL_DIR.'/load.php';
