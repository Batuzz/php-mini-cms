<?php
require_once('view/WelcomeView.php');

/**
 * @author Bartosz Studnik
 */
class WelcomeController {
    
    /**
     * Constructor with no parameters.
     * It calls WelcomeView constructor.
     */
    public function __construct() {
        
        $panel = new WelcomeView();
    }
}