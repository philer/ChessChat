<?php
///////////////////
// PAGE SETTINGS //
///////////////////

// Global page title
define('SITENAME', 'ChessChat');

// Adjust this if default doesn't work
define('HOST', $_SERVER['SERVER_NAME']);

// If you want to hide 'index.php/' from your URLs, use this option
// Requires mod_rewrite to be configured accordingly (see .htaccess)
define('SEO_URL', false);

/////////////
// OPTIONS //
/////////////

// Show quick login fields
define('QUICK_LOGIN', true);

// Username requirements apply to all new registrations
define('USERNAME_MIN_LENGTH', 3);
define('USERNAME_MAX_LENGTH', 16);

// Only allow ascii characters like letters, digits and
// common special characters
define('USERNAME_FORCE_ASCII', false);


// Games are identified by unique hashes that are part of the URL, like
// http://example.tld/Game/<game-hash>
// GAME_SALT should be a random string but is not vital for security!
define('GAME_SALT',''); // for game identification
// Game hashes are generated with here defined length. Choose a high
// enough value to avoid collision.
define('GAME_HASH_LENGTH', 6); //64^6 ~= 7e10

//////////////
// SECURITY //
//////////////

// Passwords must have a sensible length
define('PASSWORD_MIN_LENGTH', 6);

// Wait INVALID_LOGIN_WAIT seconds after an invalid login attempt
// before sending a reply to slow down brute force attempts.
define('INVALID_LOGIN_WAIT', 2);

// Additional global salt for passwords allows slightly better security
define('GLOBAL_SALT','');


////////////
// SYSTEM //
////////////

// Show verbose error messages.
define('DEBUG_MODE', false);

// Default update interval for ajax chat in seconds
// Adjust to server load. Do not use values below 1.
define('CHAT_UPDATE_INTERVAL', 3);

// Cookie names are prefixed to prevent
// collision with other applications
define('COOKIE_PREFIX', 'cc_');
// Regular cookies expire after COOKIE_DAYS days.
define('COOKIE_DAYS', '100');

