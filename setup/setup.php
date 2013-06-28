<?php
/**
 * This file creates the database layout used by chesschat.
 * It may be executed via browser or command line (e.g. git hooks).
 *
 * Browser: <site-url>/setup/setup.php[?testdata[&verbose|v]]
 * Command Line: php setup.php [testdata] [verbose|v]
 *
 * If the 'testdata' parameter is specified, queries from the
 * testdata file 'test.sql' will be included.
 * 
 * Use the 'verbose' or 'v' parameter for detailed output.
 *
 */

// server application programming interface or commandline interface?
$sapi = !defined('STDIN');
$verbose = isset($_GET['verbose'])
		|| isset($_GET['v'])
		|| in_array('verbose', $argv)
		|| in_array('v', $argv);

// html for browser usage
if ($sapi) echo '<title>database setup</title><pre>';
echo "=== DATABASE SETUP ===\n";


// init
if (!defined('ROOT_DIR')) define('ROOT_DIR', dirname(__FILE__).'/../');
if ($verbose) echo 'ROOT_DIR: ' . ROOT_DIR . "\n";

require_once(ROOT_DIR . 'lib/util.inc.php');

if (!isset($db)) {
	echo "=== connecting to Database...\n";
	include(ROOT_DIR . 'config/db.conf.php');
	try {
		$db = new Database($dbHost, $dbUser, $dbPass, $dbName);
	} catch (Exception $e) {
		echo $e->getMessage();
		exit;
	}
}


// do something
echo "=== preparing queries...\n";
$useTestdata = isset($_GET['testdata'])
		    || in_array('testdata', $argv);


$sql = file_get_contents(ROOT_DIR . 'setup/setup.sql');
$queries = explode(';', $sql);

// include test data?
if ($useTestdata) {
	echo "=== including testdata...\n";
	$sql = file_get_contents(ROOT_DIR . 'setup/test.sql');
	$queries = array_merge($queries, explode(';', $sql));
}

// go for it!
echo "=== sending queries...\n";
if ($verbose) echo "\n";
foreach ($queries as $q) {
	$q = trim($q);
	if (!empty($q)) {
		if ($verbose) echo "=== sending query: \n" . $q;
		try {
			$db->sendQuery($q);
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
		if ($verbose) echo "\n\n";
	}
}

echo "=== sent " . $db->getQueryCount() . " queries\n";
echo "=== done\n";
