<?php
define('SITENAME', 'ChessChat');
define('HOST', "http://shire/chesschat/");

//TODO security risk!
define('SALT','gm27hAhLzuQk7tF4uSzuTkGdHPBuXTZD'); // for passwords etc.
define('GAME_SALT','DECEVe4m'); // for game identification
define('GAME_HASH_LENGTH', 6); //64^6 ~= 7e10
