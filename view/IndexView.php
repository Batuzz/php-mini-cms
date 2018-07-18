<?php
require_once('PageView.php');

/**
 * @author Bartosz Studnik
 */
class IndexView extends PageView {
    
    /**
     * @var string 
     */
    private $title;
    
    /**
     * @var string 
     */
    private $content;
    
    /**
     * Constructor with two parameters. It initalizes basic variables.
     * 
     * @param string
     */
    public function __construct($title='', $content='') {

        parent::__construct();
        $this->title = $title;
        $this->content = $content;
    }
    
    
    /**
     * Method prints default content when no PanelAdmin option was chosen.
     */
    public function content($content = null) {
        ?>
                <header>
                    <h1><?=$this->title;?></h1>
                </header>
                <article>
                    <p><?=$this->content;?></p>
                </article>
        <?php
    }
}
