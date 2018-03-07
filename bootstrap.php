<?php

ini_set('display_errors', 'off');
date_default_timezone_set('Asia/Shanghai');

define('ROOT_DIR', __DIR__);
define('FRAME_DIR', ROOT_DIR.'/frame');
define('DOMAIN_DIR', ROOT_DIR.'/domain');
define('COMMAND_DIR', ROOT_DIR.'/command');
define('CONTROLLER_DIR', ROOT_DIR.'/controller');
define('DEP_DOMAIN_DIR', ROOT_DIR.'/dep_domain');
define('DEP_CLIENT_DIR', ROOT_DIR.'/dep_client');

include FRAME_DIR.'/function.php';
include FRAME_DIR.'/otherwise.php';
include FRAME_DIR.'/unitofwork.php';

config_dir(ROOT_DIR.'/config');
unit_of_work_db_config_key('distributed');

include ROOT_DIR.'/util/load.php';
include ROOT_DIR.'/domain/load.php';
include ROOT_DIR.'/client/client.php';

include DEP_DOMAIN_DIR.'/load.php';
include DEP_CLIENT_DIR.'/load.php';
