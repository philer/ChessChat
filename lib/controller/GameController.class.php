<?

/**
 * The Game is important.
 * @author Philipp Miller
 */
class GameController implements StandaloneController, AjaxController {
	
	public function handleStandaloneRequest() {
		// TODO
		$this->toTpl();
	}
	
	public function toTpl() {
		require_once(ROOT_DIR.'template/game.tpl.php');
	}
	
	public function handleAjaxRequest() {}
	
	protected function getAjaxUpdate() {}
	
}
