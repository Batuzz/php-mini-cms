<?php
require_once('DatabaseController.php');
require_once('model/Question.php');
require_once('model/Navigation.php');
require_once('model/Gender.php');
require_once('view/QuizView.php');
require_once('exceptions/NoDataException.php');

/**
 * This controller is reponsible for printing questions on website.
 * It calls `QuizView` class' methods.
 *
 * @author Bartosz Studnik
 */
class QuestionController {
    
    /**
     * @var Database Connection
     */
    private $dbConnection;
    
    /**
     * Constructor with no parameters. It connects with Database.
     * It's responsible for calling `QuizView` class' methods.
     */
    public function __construct() {
        
        $this->dbConnection = new DatabaseController();
        
        $panel = new QuizView();
        $navigation = new Navigation($this->dbConnection->selectPages(), $this->dbConnection->selectMenu());
        $panel->header('Quiz', $navigation->getNav());
        
        $exception = false;
        $questions = $this->dbConnection->selectQuestions();
        try {
            
            $gender = new Gender($_SESSION['quizGender']);
            $panel->content('Nick: '.$_SESSION['quizNick'].'<br />'.AGE.': '.$_SESSION['quizAge'].'<br />'.GENDER.': '.$gender->getGender());            
            
        } catch (NoDataException $ex) {
            $panel->setError(ERROR, ERROR_NO_QUESTIONS_TEXT);
        } finally {
            if ($exception) {
                $panel->showError();
            } else {
                $panel->startForm();
                
                if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'en') {
                    while ($quiz = $questions->fetch()) {                        
                        $panel->showQuiz($quiz['id'], $quiz['answer1en'], $quiz['answer2en'], $quiz['contenten'], Form::quizRadio("q".$quiz['id']));
                    }
                } else {
                    while ($quiz = $questions->fetch()) {                    
                        $panel->showQuiz($quiz['id'], $quiz['answer1'], $quiz['answer2'], $quiz['content'], Form::quizRadio("q".$quiz['id']));
                    }
                }
                $panel->endForm();
            }
            $panel->footer();
        }        
    }
}