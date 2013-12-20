<?php
/**
 * Magic function autoload is called whenever an
 * unknown class is requested.
 * It checks for a <classname>.(class|if).php file in
 * all known directories (inside lib/) and includes it.
 * @author Philipp Miller
 */
function __autoload($className) {
    
    // add new directories here
    $dirs = array(
        'lib/',
        'lib/controller/',
        'lib/model/',
        'lib/model/chess/',
        'lib/exception/',
    );
    
    // try all directories for class.php
    foreach ($dirs as $dir) {
        if (file_exists(ROOT_DIR.$dir.$className.'.class.php')) {
            require_once(ROOT_DIR.$dir.$className.'.class.php');
            return;
        }
    }
    
    // try all directories for if.php
    foreach ($dirs as $dir) {
        if (file_exists(ROOT_DIR.$dir.$className.'.if.php')) {
            require_once(ROOT_DIR.$dir.$className.'.if.php');
            return;
        }
    }
}
