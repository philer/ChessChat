<?php
define('SITENAME', 'ChessChat');
// TODO obsolete: $_SERVER[] -> HTTP_HOST, REQUEST_SCRIPTNAME
define('HOST', "");

//TODO security risk!
define('GLOBAL_SALT',''); // for passwords
define('GAME_SALT',''); // for game identification
define('GAME_HASH_LENGTH', 6); //64^6 ~= 7e10

define('DEBUG_MODE', false);
