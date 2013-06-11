<?php

/**
 * Index page, often also called 'Home'
 * @author Philipp Miller
 */
class IndexController implements StandaloneController {
	
	public function handleStandaloneRequest() {
		Core::getTemplateEngine()->registerStylesheet("game");
		Core::getTemplateEngine()->show("index");
	}
	
}
