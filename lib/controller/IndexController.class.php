<?php

/**
 * Index page, often also called 'Home'
 * @author Philipp Miller
 */
class IndexController extends AbstractRequestController {
    
    /**
     * Show a simple index page
     * @param  array  $route
     */
    public function handleRequest(array $route) {
        $this->pageTitle = Core::getLanguage()->getLanguageItem('site.index');
        Core::getTemplateEngine()->registerStylesheet("game");
        Core::getTemplateEngine()->showPage("index", $this);
    }
    
    /**
     * Startpage doesn't need a route
     * @return  string  empty string
     */
    public function getCanonicalRoute() {
        return '';
    }
}
