<?php

/**
 * Index page, often also called 'Home'
 * @author Philipp Miller
 */
class IndexController extends AbstractRequestController {
    
    public function handleRequest(array $route) {
        $this->pageTitle = Core::getLanguage()->getLanguageItem('site.index');
        Core::getTemplateEngine()->registerStylesheet("game");
        Core::getTemplateEngine()->showPage("index", $this);
    }
    
    public function getCanonicalRoute() {
        return '';
    }
}
