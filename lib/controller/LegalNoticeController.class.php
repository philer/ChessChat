<?php

/**
 * Represents the legal notice page.
 * This class is a simple example for a Controller.
 * @author Philipp Miller
 */
class LegalNoticeController extends AbstractRequestController {
    
    public function handleRequest(array $route) {
        $this->pageTitle = Core::getLanguage()->getLanguageItem('site.legalnotice');
        include(ROOT_DIR . 'config/legal.conf.php');
        Core::getTemplateEngine()->addVar('legal',$legalInfo);
        Core::getTemplateEngine()->showPage('legalNotice', $this);
    }
    
}
