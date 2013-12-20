<?php
/**
 * This script generates SQL queries with
 * random users and games for chesschat.
 * Don't try to access this script directly via browser or commandline!
 * @see  setup.php
 * @author  Philipp Miller
 */

// options
$userN = 250;
$gameN = 250;
$cmsgN = 2500;
$langs = array('en', 'de', '--', 'fr');

$langN = count($langs);

// generate users
$userQuery = 'INSERT INTO cc_user (userName, email, password, language) VALUES';
for ($u=1 ; $u <= $userN ; $u++) {
    $name = Util::getRandomString(3, 12,'.-_ ');
    $userQuery .= "\n('" . $name . "', "
                . "'" . urlencode($name) . "@" . Util::getRandomString(4,8) . "." . Util::getRandomString(2,3)
                . "', '$2y$08$100010001000100010001.LNsjk4vI3U65IdmjIEugZ2MnFxHt1De', " // 'password'
                . "'" . $langs[mt_rand(0, $langN-1)] ."'), ";
}
$userQuery = rtrim($userQuery, ', ');
$queries[] = $userQuery;

// generate games
$gamesData = array();
$gameQuery = 'INSERT INTO cc_game (gameHash, whitePlayerId, blackPlayerId, board, status) VALUES';
for ($g=1 ; $g <= $gameN ; $g++) {
    $gamesData[$g] = array(mt_rand(1,$userN), mt_rand(1,$userN));
    $gameQuery .= "\n('" . Util::getRandomString(6) . "', "
                . $gamesData[$g][0] . ", "
                . $gamesData[$g][1] . ", "
                . "'" . Board::DEFAULT_STRING . "', "
                . "'" . mt_rand(0, 15) . "'), ";
}
$gameQuery = rtrim($gameQuery, ', ');
$queries[] = $gameQuery;

// generate chat messages
$cmsgQuery = 'INSERT INTO cc_chatMessage (gameId, authorId, messageText) VALUES';
for ($cm=1 ; $cm <= $cmsgN ; $cm++) {
    $gameId = mt_rand(1,$gameN);
    $cmsgQuery .= "\n(" . $gameId . ", "
                . $gamesData[$gameId][mt_rand(0,1)] . ", "
                . "'testmsg " . Util::getRandomString(0,255,' ') . "'), ";
}
$cmsgQuery = rtrim($cmsgQuery, ', ');
$queries[] = $cmsgQuery;
