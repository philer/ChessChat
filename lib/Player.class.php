<?php
abstract class Player {
	
	/**
	 * unique PlayerId
	 * @var integer
	 */
	protected $id;
	
	/**
	 * A Player has a name.
	 * @var string
	 */
	protected $name;
	
	/**
	 * unique PlayerHash for cookie identification
	 * @var string
	 */
	protected $hash;
	
	/**
	 * Player white?
	 * @var boolean
	 */
	protected $white;
	
	
	public function __construct($id, $name = '', $hash = '') {
		
	}
}
