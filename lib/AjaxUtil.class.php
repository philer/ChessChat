<?php

/**
 * Wraps answers to an ajax request (compare how
 * TemplateEngine takes care of regular requests)
 * @author Philipp Miller
 */
class AjaxUtil {
    
    /**
     * All replies for this request
     * @var array<mixed>
     */
    private static $reply = array();
    
    /**
     * Collects all replies for this request in an associative array.
     * @param     string     key
     * @param     string     value
     */
    final public static function queueReply($key, $value) {
        if (!array_key_exists($key, self::$reply)) {
            self::$reply[$key] = $value;
        } else throw new FatalError('tried to overwrite ajax reply');
    }
    
    /**
     * Last step in the chain of events: Send the complete
     * ajax reply as a JSON array.
     */
    final public static function sendReply() {
        header('Content-Type: application/json');
        echo json_encode(self::$reply);
    }
    
}
