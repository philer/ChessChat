<?php

/**
 * Fatal Exception for Database problems
 * @author Philipp Miller
 */
class DatabaseException extends FatalException {
	
	public function __construct($message) {
		$this->title = "Database Exception";
		parent::__construct($message);
	}
}
