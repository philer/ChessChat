<?php

/**
 * Represents the legal notice page.
 * This class is a simple example for a Controller.
 * @author Philipp Miller
 */
class LegalNoticeController implements RequestController {
	
	public function handleRequest() {
		include(ROOT_DIR."config/legal.conf.php");
		Core::getTemplateEngine()->addVar('legal',$legalInfo);
		Core::getTemplateEngine()->showPage("legalNotice");
	}
	
}
