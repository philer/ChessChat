<?php

/**
 * A ajax controller can respond to an ajax request
 * @author Philipp Miller
 */
abstract class AjaxController {
	
	/**
	 * All replies for this request
	 * @var array<mixed>
	 */
	private static $reply = array();
	
	/**
	 * If the incoming Request is an AJAx request, an AjaxController's
	 * handleAjaxRequest method will be called for response.
	 */
	abstract public function handleAjaxRequest();
	
	/**
	 * Collects all replies for this request in an associative array.
	 * @param 	string 	key
	 * @param 	string 	value
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
		echo json_encode(self::$reply);
	}
	
}
