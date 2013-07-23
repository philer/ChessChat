<?php
//////////////
// SETTINGS //
//////////////

define('SITENAME', 'ChessChat');
define('HOST', ''); // TODO $_SERVER[] -> HTTP_HOST, REQUEST_SCRIPTNAME

/////////////
// OPTIONS //
/////////////

define('QUICK_LOGIN', true);

define('USERNAME_MIN_LENGTH', 3);
define('USERNAME_MAX_LENGTH', 16);

define('GAME_SALT',''); // for game identification
define('GAME_HASH_LENGTH', 6); //64^6 ~= 7e10


//////////////
// SECURITY //
//////////////

define('PASSWORD_MIN_LENGTH', 6);
define('INVALID_LOGIN_WAIT', 2);

// Global salt for some additional security
define('GLOBAL_SALT',''); // for passwords


////////////
// SYSTEM //
////////////

define('DEBUG_MODE', false);

// Cookie names are prefixed to prevent
// collision with other applications
define('COOKIE_PREFIX', 'cc_');
// Regular cookies expire after COOKIE_DAYS days.
define('COOKIE_DAYS', '100');
