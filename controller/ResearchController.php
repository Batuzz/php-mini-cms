<?php
require_once('DatabaseController.php');
require_once('model/Question.php');
require_once('model/Navigation.php');
require_once('view/QuizView.php');
require_once('view/Form.php');

/**
 * This controller is responsible for collecting quiz input data.
 *
 * @author Bartosz Studnik
 */
class ResearchController {
    
    /**
     * @var Database Connection
     */
    private $dbConnection;  
    
    
    
    /**
     * Constructor with no parameters.
     * It prepares and prints forms that are responsible for collectiong quiz input data.
     */
    public function __construct() {
        
        $panel = new QuizView();
        
        $headerTitle = 'e-parchment.net - Quiz';
        $exception = false;
        try {
            $this->dbConnection = new DatabaseController();
            $formArray = array(
                array('type' => 'text', 'label' => 'Nick: ', 'name' => 'nick', 'id'=>'nick', 'value' => ''),
                array('type' => 'text', 'label' => AGE.': ', 'name' => 'age', 'id'=>'age', 'value' => ''),
                array('type' => 'select', 'label' => GENDER.': ', 'name' => 'gender', 'id'=>'gender', 'options'=>array(
                    '-'=>array('value'=>SEX_PICK), 
                    '0'=>array('value'=>SEX_FEMALE),
                    '1'=>array('value'=>SEX_MALE),
                )),
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
                $panel->content(QUIZ_CONTENT.$specialContent);
            }
            $panel->footer();
        }
    }
}