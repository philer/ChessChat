<?

/**
 * Represents a single chat conversation.
 * @author Philipp Miller
 */
class Chat {
	
	/**
	 * Chat gets logged.
	 * @var 	string
	 */
	$logFileName = "";
	
	/**
	 * All messages that have been written so far.
	 * @var 	array
	 */
	$messages = array();
	
	/**
	 * TODO
	 */
	public function __construct() {
		/*
		$fp = fopen($logfile, 'a');
		fwrite($fp,$_POST["msg"]);
		fclose($fp);
		*/
		/*
		$msgTime = $_POST["msgTime"];
		$playerName = $_POST["playerName"];
		$msg = $_POST["msg"];
		*/
		/*
		require_once("../templates/chatMessage.tpl.php");
		*/
	}
	
	public function toTpl() {
		require_once(ROOT_DIR.'template/chat.tpl.php');
	}
	
	public function getNewMessages() {}
	public function getAllMessages() {}
	public function broadcastMessage() {} // broadcast == write to file
}
