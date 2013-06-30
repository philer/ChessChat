<?php

define('SITENAME', 'ChessChat');

define('HOST', '');
// TODO obsolete: $_SERVER[] -> HTTP_HOST, REQUEST_SCRIPTNAME

// options
define('QUICK_LOGIN', true);

define('DEBUG_MODE', false);


// Salts are for additional security
// Global salts should never be used alone!
define('GLOBAL_SALT',''); // for passwords
define('GAME_SALT',''); // for game identification
define('GAME_HASH_LENGTH', 6); //64^6 ~= 7e10

// Cookie names are prefixed to prevent
// collision with other applications
define('COOKIE_PREFIX', 'cc_');
// Regular cookies expire after COOKIE_DAYS days.
define('COOKIE_DAYS', '100');
