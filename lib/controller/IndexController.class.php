<?php

/**
 * Index page, often also called 'Home'
 * @author Philipp Miller
 */
class IndexController implements RequestController {
	
	public function handleRequest() {
		Core::getTemplateEngine()->registerStylesheet("game");
		Core::getTemplateEngine()->showPage("index");
	}
	
}
