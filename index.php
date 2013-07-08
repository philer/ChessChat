<?php

// full error reporting
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// benchmarking
define('START_TIME', microtime(true));
// current time
define('NOW', time());
// for absolute include paths
define('ROOT_DIR', dirname(__FILE__) . '/');

// load global configs
require_once(ROOT_DIR . 'config/global.conf.php');
// load utility functions
require_once(ROOT_DIR . 'lib/autoload.inc.php');

// use custom exception and error handling
set_exception_handler(array('Core', 'exceptionHandler'));
set_error_handler(array('Core', 'errorHandler'));

// start the system
new Core(); 
