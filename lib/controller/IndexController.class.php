<?php

/**
 * Index page, often also called 'Home'
 * @author Philipp Miller
 */
class IndexController implements StandaloneController {
	
	public function handleStandaloneRequest() {
		Core::getTemplateEngine()->registerStylesheet("page");
		Core::getTemplateEngine()->registerStylesheet("game");
		Core::getTemplateEngine()->registerStylesheet("gameColor");
		Core::getTemplateEngine()->show("index");
	}
	
}
