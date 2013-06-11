<?php

error_reporting(E_ALL|E_STRICT);
ini_set('display_errors',1);
ini_set('display_startup_errors',1);

define('START_TIME',microtime(true)); // benchmarking

if (!defined('ROOT_DIR')) define('ROOT_DIR', dirname(__FILE__).'/');
require_once(ROOT_DIR.'config/global.conf.php');

require_once(ROOT_DIR.'lib/Core.class.php');
new Core(); // start init process but never use Core as an object
