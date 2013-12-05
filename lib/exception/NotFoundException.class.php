<?php

/**
 * Represents a regular 404 Not Found error.
 * Output is dependent on DEBUG_MODE.
 */
class NotFoundException extends RequestException {
    
    /**
     * Create a 404 Error
     * @param  string  $message  error message
     */
    public function __construct($message = '') {
        $this->title = 'Not Found';
        $this->httpCode = 404;
        header('HTTP/1.0 404 Not Found');
        parent::__construct($message);
    }
}
