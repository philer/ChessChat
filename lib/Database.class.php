<?php

/**
 * Represents a Database connection.
 * Currently only supports MySQL Databases using the MySQLi extension.
 * If different database types are to be supported, make this abstract
 * and extend appropriately!
 * @author Philipp Miller
 */
class Database {
	
	/**
	 * database authentification information
	 * @var string
	 */
	protected $host, $user, $pass, $name;
	
	/**
	 * MySQLi connection object
	 * @var MySQLi
	 */
	protected $db = null;
	
	/**
	 * number of sent queries for benchmarking
	 * @var integer
	 */
	protected $queryCount = 0;
	
	/**
	 * all queries that have been sent so far
	 * for debugging and benchmarking purposes
	 * @var array<string>
	 */
	protected $sentQueries = array();
	
	/**
	 * Creates a new database connection
	 * @param 	string 	$host 	database hostname/server adress (e.g. 'localhost')
	 * @param 	string 	$user 	database user name (sometimes same as database name)
	 * @param 	string 	$pass 	database user password
	 * @param 	string 	$name 	database name
	 */
	public function __construct($host, $user, $pass, $name) {
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->name = $name;
		
		$this->connect();
	}
	
	/**
	 * Connects to database using given authentication information.
	 * Override this for different database types!
	 */
	protected function connect() {
		$this->db = new MySQLi($this->host, $this->user, $this->pass, $this->name);
		if ($this->db->connect_error) {
			throw new DatabaseException("Database Connection Failed: ".mysqli_connect_error());
		}
		$this->db->set_charset("utf8");
	}
	
	/**
	 * Sends a query and return the result
	 * @param 	string 	$query 	a SQL query
	 * @return 	array
	 */
	public function sendQuery($query) {
		$result = $this->db->query($query);
		$this->queryCount++;
		$this->sentQueries[] = $query;
		if(!$result) {
			$errmsg = "<p>Sending Query "
					 ."'<span style='font-size:0.8em;'>".$query."</span>' "
					 ."failed.</p>"
					 ."<p>".$this->db->error."</p>";
			throw new DatabaseException($errmsg);
			//echo $this->db->error;
		}
		else return $result;
	}
	
	/**
	 * Use this for Database security!
	 * @see SQL Injections
	 * @param 	string 	$string
	 * @return 	string
	 */
	public function escapeString($string) {
		return $this->db->real_escape_string($string);
	}
	
	/**
	 * Closes the database connection
	 */
	public function closeDB() {
		$dbObj->close();
	}
	
	/**
	 * Returns total number of queries sent by now.
	 * for debugging and benchmarking purposes
	 * @return 	integer
	 */
	public function getQueryCount() {
		return $this->queryCount;
	}
	
	/**
	 * Returns an array containing all queries that have been sent by now as a string array.
	 * for debugging and benchmarking purposes
	 * @return 	array<string>
	 */
	public function getSentQueries() {
		return $this->sentQueries;
	}
	
}
