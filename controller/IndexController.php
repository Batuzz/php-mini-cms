<?php
require_once('view/IndexView.php');
require_once('model/Navigation.php');
require_once('DatabaseController.php');

/**
 * This controller is responsible for managing `index.php` files.
 * 
 * @author Bartosz Studnik
 */
class IndexController {
    
    /**
     * @var Database Connection 
     */
    private $dbConnection;
    
    /**
     * @var string 
     */
    private $title;
    
    /**
     * @var string 
     */
    private $content;
    
    /**
     * @var string 
     */
    private $header;
    
    
    /**
     * Constructor with three parameters. It sets website's `title`, `content` and `header`.
     * It connects with database.
     * 
     * @param string
     * @param string
     * @param string
     */
    public function __construct($title='', $content='', $header='') {
        
        $this->dbConnection = new DatabaseController();
        
        $this->title = $title;
        $this->content = $content;
        $this->header = $header;
        
        $navigation = new Navigation($this->dbConnection->selectPages(), $this->dbConnection->selectMenu());
        $view = new IndexView($this->header, $this->content);

        $view->header($this->title, $navigation->getNav());
        $view->content();
        $view->footer();
    }
}
