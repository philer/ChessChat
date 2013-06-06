<?php

/**
 * The Game is important.
 * @author Philipp Miller
 */
class GameController implements StandaloneController, AjaxController {
	
	public function handleStandaloneRequest() {

		// new Game(); // TODO

		Core::getTemplateEngine()->registerScript("chessboardLayout");
		Core::getTemplateEngine()->registerScript("chat");
		Core::getTemplateEngine()->registerStylesheet("game");
		Core::getTemplateEngine()->show("game");
	}
	
	public function handleAjaxRequest() {}
	
	protected function getUpdate() {}
	
}
