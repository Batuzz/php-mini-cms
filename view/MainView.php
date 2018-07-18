<?php
require_once('PageView.php');
require_once('Form.php');
require_once('exceptions/WrongIDException.php');

/**
 * Class responsible for printing main site.
 *
 * @author Bartosz Studnik
 */
class MainView extends PageView {
   
    /**
     * Constructor with one parameter.
     * 
     * @param string
     */
    public function __construct() {

        parent::__construct();
    }
    
    
    /**
     * Method prints default content when no PanelAdmin option was chosen.
     */
    public function content($content = null) {
        ?>
                <header> 
                    <h1><?=$content->getTitleLang();?></h1>
                </header>
                <article>
                    <p><?=$content->getContentLang();?></p>
                </article>
        <?php
    }
    
}