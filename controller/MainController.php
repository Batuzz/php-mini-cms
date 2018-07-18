<?php
require_once('DatabaseController.php');
require_once('model/User.php');
require_once('exceptions/AccessException.php');
require_once('model/Content.php');
require_once('model/Question.php');
require_once('model/Page.php');
require_once('model/Menu.php');
require_once('model/Navigation.php');
require_once('view/MainView.php');

/**
 * This controller is responsible for calling MainView methods and managing main subpages' content.
 *
 * @author Bartosz Studnik
 */
class MainController {
    
    /**
     * @var Database Connection
     */
    private $dbConnection;  
    
    
    
    /**
     * Constructor with no parameters. It connects with Database.
     * It's responsible for managing main subpages' content.
     */
    public function __construct() {
        
        $panel = new MainView();
        
        $headerTitle = 'e-parchment.net - '.TOPICS;
        $exception = false;
        try {

            $this->dbConnection = new DatabaseController();

            if (isset($_GET['pageId'])) {                
                $pageId = (int) $_GET['pageId'];
            } else {                
                $pageId = 1;
            }
            $contents = $this->dbConnection->selectContentsByPageId($pageId);
   
        } catch(ContentException $ex) {
            $panel->setError(ERROR_NO_CONTENT, ERROR_NO_CONTENT_TEXT);
            $exception = true;
        } catch(NoDataException $ex) {
            $panel->setError(ERROR_NO_CONTENT, ERROR_NO_DATA_TEXT);
            $exception = true;
        } catch(PDOException $ex) {
            $panel->setError(ERROR.'#2.', ERROR_TEXT);
            $exception = true;
        } finally {     
            $navigation = new Navigation($this->dbConnection->selectPages(), $this->dbConnection->selectMenu());
            $panel->header($headerTitle, $navigation->getNav());
            if ($exception) {
                $panel->showError();
            } else {
                while ($c = $contents->fetch()) {
                    $content = new Content($this->dbConnection);
                    $content->setContentById($c['id']);
                    $panel->content($content);
                }
            }
            $panel->footer();
        }
    }
}