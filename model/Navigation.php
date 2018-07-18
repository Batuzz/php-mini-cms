<?php

/**
 * Class manages `navigation` content.
 *
 * @author Bartosz Studnik
 */
class Navigation {
    
    /**
     * @var string array
     */
    private $nav;
    
    /**
     * Constructor with two parameters. Calls method that initializes `nav` array.
     * 
     * @param pagesQuery
     * @param menuQuery
     */
    public function __construct($pages, $menu) {
        $this->setPagesToMenu($pages, $menu);
    }
    
    
    /**
     * Method that initializes `nav` array.
     * 
     * @param pagesQuery
     * @param menuQuery
     */
    public function setPagesToMenu($pages, $menu){
        
        $this->nav = array();
        while($e = $menu->fetch()) {
            $this->nav[$e["id"]] = $e;
            $this->nav[$e["id"]]["pages"] = array();
        }
        while($e2 = $pages->fetch()) {
            $this->nav[$e2["sectionid"]]["pages"][] = $e2;
        }
    }
    
    
    
    public function getNav() {
        return $this->nav;
    }
    
}