<?php
require_once('view/Form.php');
require_once('view/AdminLoginView.php');
require_once('model/Navigation.php');
/**
 * It's repsponsible for picking login data through two inputs and saves it in `$_SESSION` superglobal variable.
 * 
 * @author Bartosz Studnik
 */
class AdminLoginController {
    
    /** 
     * @var Database connection
     */
    private $dbConnection;
    
    
    
    /**
     * Constructor with no parameters. It connects with database and saves login data in `$_SESSION` superglobal variable.
     */
    public function __construct() {
        
        $panel = new AdminLoginView();
        $this->dbConnection = new DatabaseController();
        
        $headerTitle = 'e-parchment.net - Admin Panel';
        $exception = false;
        try {
            $formArray = array(
                array('type' => 'text', 'label' => 'Login: ', 'name' => 'accountname', 'id'=>'accountname', 'value' => ''),
                array('type' => 'password', 'label' => 'Hasło: ', 'name' => 'password', 'id'=>'password', 'value' => ''),
            );
            $form = new Form($formArray, '', 'POST');
            $specialContent = $form->createForm();
        } catch(PDOException $ex) {
            $panel->setError(ERROR.'#2.', ERROR_TEXT);
            $exception = true;
        } finally {
            $navigation = new Navigation($this->dbConnection->selectPages(), $this->dbConnection->selectMenu());
            $panel->header($headerTitle, $navigation->getNav());
            if ($exception) {
                $panel->showError();
            } else {
                $panel->content($specialContent);
            }
            $panel->footer();
        }
        
        if(isset($_POST['accountname']) && isset($_POST['password'])) {
            $_SESSION['accountname'] = $_POST['accountname'];
            $_SESSION['password'] = $_POST['password'];
        }
    }
}