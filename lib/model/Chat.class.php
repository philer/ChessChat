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
	protected $logFileName = "";
	
	/**
	 * All messages that have been written so far.
	 * @var 	array
	 */
	protected $messages = array();
	
	/**
	 * TODO
	 */
	public function __construct() {
		/*
		$fp = fopen($logfile, 'a');
		fwrite($fp,$_POST["msg"]);
		fclose($fp);
		*/
	}
	
	/**
	 * A user has sent a new chat message which will be stored
	 * and set up for broadcast.
	 * @param 	string 	$msg
	 * @param 	string 	$bot
	 */
	public function post($msg, $bot = null) {
		// TODO save & broadcast
		Core::getTemplateEngine()->addVar('msg',$msg);
		if (isset($bot)) Core::getTemplateEngine()->addVar('bot',$bot);
		Core::getTemplateEngine()->show("chatMessage");
	}
	
	//TODO
	public function getNewMessages() {}
	public function getAllMessages() {}
	public function broadcastMessage() {} // broadcast == write to file
}
