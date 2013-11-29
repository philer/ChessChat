<?php
//////////////
// SETTINGS //
//////////////

define('SITENAME', 'ChessChat');
define('HOST', $_SERVER['SERVER_NAME']);
define('SEO_URL', false); // hide index.php for use with mod_rewrite

/////////////
// OPTIONS //
/////////////

define('QUICK_LOGIN', true);

define('USERNAME_MIN_LENGTH', 3);
define('USERNAME_MAX_LENGTH', 16);
define('USERNAME_FORCE_ASCII', false);

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

// Default update interval for ajax chat in seconds
define('CHAT_UPDATE_INTERVAL', 3);
