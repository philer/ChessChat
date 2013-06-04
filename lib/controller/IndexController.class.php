<?

/**
 * Index page, often also called 'Home'
 * @author Philipp Miller
 */
class IndexController implements StandaloneController {
	
	public function handleStandaloneRequest() {
		// TODO
		$this->toTpl();
	}
	
	public function toTpl() {
		require_once(ROOT_DIR.'template/game.tpl.php');
	}
	
}
