<?php
/**
 * This file creates the database layout used by chesschat.
 * It may be executed via browser or command line (e.g. git hooks).
 *
 * Browser: <site-url>/setup/setup.php[?option1&option2...]
 * Command Line: php setup.php [options...]
 * 
 * @param  verbose, v     echo verbose output (including queries)
 * @param  reset         delete all tables and recreate (setup.sql)
 * @param  testdata        include testdata (test.sql)
 * @param  random         include randomized testdata (random.php)
 * 
 * @author  Philipp Miller
 */

// full error reporting
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// server application programming interface or commandline interface?
$sapi = !defined('STDIN');
if ($sapi) $argv = array();
$verbose = isset($_GET['verbose'])
        || isset($_GET['v'])
        || in_array('verbose', $argv)
        || in_array('v', $argv);

// html for browser usage
if ($sapi) echo '<title>database setup</title><pre>';
echo "=== DATABASE SETUP ===\n";


//////////
// INIT //
//////////
if (!defined('ROOT_DIR')) define('ROOT_DIR', dirname(__FILE__).'/../');
if ($verbose) echo 'ROOT_DIR: ' . ROOT_DIR . "\n";

require_once(ROOT_DIR . 'lib/autoload.inc.php');

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

//////////////////
// do something //
//////////////////
echo "=== preparing queries...\n";
$queries = array();

/////////////////////
// reset database? //
/////////////////////
if (isset($_GET['reset']) || in_array('reset', $argv)) {
    echo "=== resetting database...\n";
    $sql = file_get_contents(ROOT_DIR . 'setup/setup.sql');
    $queries = array_merge($queries, explode(';', $sql));
}

///////////////////////////////////
// include randomized test data? //
///////////////////////////////////
if (isset($_GET['random']) || in_array('random', $argv)) {
    echo "=== including random testdata...\n";
    include(ROOT_DIR . 'setup/random.php');
}

////////////////////////
// include test data? //
////////////////////////
if (isset($_GET['testdata']) || in_array('testdata', $argv)) {
    echo "=== including testdata...\n";
    $sql = file_get_contents(ROOT_DIR . 'setup/test.sql');
    $queries = array_merge($queries, explode(';', $sql));
}

////////////////
// go for it! //
////////////////
echo "=== sending queries...\n";
if ($verbose) echo "\n";
foreach ($queries as $q) query($q);

echo "=== sent " . $db->getQueryCount() . " queries\n";
echo "=== done\n";



//////////
// UTIL //
//////////

function query($query) {
    global $verbose, $db;
    $query = trim($query);
    if (!empty($query)) {
        if ($verbose) echo "=== sending query: \n" . $query;
        try {
            $db->sendQuery($query);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        if ($verbose) echo "\n\n";
    }
}
