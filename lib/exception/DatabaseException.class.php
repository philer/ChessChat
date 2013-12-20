<?php

/**
 * Fatal Exception for Database problems
 * @author Philipp Miller
 */
class DatabaseException extends FatalException {
    
    /**
     * Fatal Exceptions should be thrown by Database classes
     * @param  string  $message  error message
     */
    public function __construct($message) {
        $this->title = 'Database Exception';
        parent::__construct($message);
    }
}
