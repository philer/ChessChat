<?php

/**
 * Use this Exception to return an HTTP error page (like 404 or 403).
 * Output verbosity depends on DEBUG_MODE.
 * By default this sends a status code of 200 (successful request)
 */
class RequestException extends Exception implements RequestController {
    
    /**
     * Name of the HTTP status 
     * @var string
     */
    protected $title = '';
    
    /**
     * HTTP status
     * @var integer
     */
    protected $httpCode = 200;
    
    /**
     * Creates a new RequestException.
     * Use this when overriding for setting the $message
     * @param     string     $message
     */
    public function __construct($message) {
        $this->message = $message;
        // http_response_code($this->http_code); // PHP 5.4
    }
    
    /**
     * Display a nice message to user.
     * Detailed messages in debug mode only.
     */
    public function show() {
        if (DEBUG_MODE) {
            Core::getTemplateEngine()->addVar('errorTitle', $this->getTitle());
            Core::getTemplateEngine()->addVar('errorMessage', $this->message);
        } else {
            Core::getTemplateEngine()->addVar('errorMessage', 'exception.' . $this->httpCode . '.msg');
        }
        Core::getTemplateEngine()->showPage('errorPage', $this);
    }
    
    /**
     * Prepends existing http response code to title
     * @return     string
     */
    public function getTitle() {
        if ($this->httpCode >= 400) {
            return $this->httpCode . ' ' . $this->title;
        } else {
            return $this->title;
        }
    }
    
    /**
     * Returns the name of the HTTP status
     * @return string
     */
    public function getPageTitle() {
        return $this->getTitle();
    }
    
    /**
     * Errors shouldn't have route, redirect to index instead
     * @return  string  empty string
     */
    public function getCanonicalRoute() {
        return '';
    }
    
    /**
     * RequestExceptions implement RequestController but
     * shouldn't be called as a request handler. Hence
     * this method does nothing.
     * @param  array  $route
     */
    public function handleRequest(array $route) {}
}
