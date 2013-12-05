<?php

/**
 * Represents a regular 403 Permission Denied Error.
 * Output is dependent on DEBUG_MODE.
 */
class PermissionDeniedException extends RequestException {
    
    /**
     * Create a 403 Error
     * @param  string  $message  error message
     */
    public function __construct($message = '') {
        $this->title = 'Permission Denied';
        $this->httpCode = 403;
        header('HTTP/1.0 403 Forbidden');
        parent::__construct($message);
    }
}
