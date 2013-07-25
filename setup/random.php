<?php
/**
 * This script generates SQL queries with
 * random users and games for chesschat.
 * Don't try to access this script directly via browser or commandline!
 * @see  setup.php
 * 
 * @author  Philipp Miller
 * 
 */

// options
$userN = 250;
$gameN = 250;
$langs = array('en', 'de', '--');

// generate users
$userQuery = 'INSERT INTO cc_user (userName, email, password, language) VALUES';
for ($u=0 ; $u < $userN ; $u++) {
	$name = rstr(10);
	$userQuery .= "\n ('". $name ."', "
	            . "'".     $name."@".rstr(5,false,false).".".rstr(2,false,false) ."', "
	            . "'password', "
	            . "'". $langs[rand(0, count($langs)-1)] ."'),";
}
$userQuery = rtrim($userQuery, ', ');
$queries[] = $userQuery;

// generate games
$gameQuery = 'INSERT INTO cc_game (gameHash, whitePlayerId, blackPlayerId, board, status) VALUES';
for ($g=0 ; $g<$gameN ; $g++) {
	$gameQuery .= "\n ('". rstr(6) ."', "
	            . "'". rand(1, $userN) ."', "
	            . "'". rand(1, $userN) ."', "
	            . "'Ra1Nb1Bc1Qd1Kd1Bc1Nb1Ra1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7ra8nb8bc8qd8kd8bc8nb8ra8', "
	            . "'". rand(0,15) ."'),";
}
$gameQuery = rtrim($gameQuery, ', ');
$queries[] = $gameQuery;
