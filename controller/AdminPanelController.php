<?php
require_once('DatabaseController.php');
require_once('model/User.php');
require_once('exceptions/AccessException.php');
require_once('model/Content.php');
require_once('model/Question.php');
require_once('model/Page.php');
require_once('model/Menu.php');
require_once('view/AdminPanelView.php');
require_once('model/Navigation.php');

/**
 * This controller is responsible for starting session, logging `User` in, checking if `User` has got enough access level and prepares Admin Panel inputs.
 *
 * @author Bartosz Studnik
 */
class AdminPanelController {
    
    /**
     * @var Database Connection
     */
    private $dbConnection;
    
    /**
     * @var User
     */
    private $user;
    
    
    
    /**
     * Constructor with no parameters. Checks if any user is currently logged in.
     * Connects with Database.
     * It's responsible for calling AdminPanelView methods.
     * 
     * @throws AccessException when `User` has insufficient permission.
     */
    public function __construct() {
        $this->dbConnection = new DatabaseController();
        $this->user = new User($this->dbConnection);
        if($userId = $this->sessionCheck()) {
            $this->user->setUserById($userId);
        } else {
            $this->user->setUserByLoginData($_SESSION['accountname'], $_SESSION['password']);
            $this->setSession($this->user->getId());
        }        
        if($this->user->getAccess() != 2) {
            throw new AccessException();
        }
        
        $panel = new AdminPanelView;
        $headerTitle = 'Panel Administratora';
        
        try {
            $do = true;
            if(isset($_GET['do'])) {
                $do = $_GET['do'];
            }
            switch($do)
            {
                case 'editUsers':
                    $showMethod = 'showUsers';
                    $headerTitle = 'Zarządzaj użytkownikami';
                    $panel->setContent('user', $this->dbConnection->selectUsers());
                    break;
                
                case 'editContent':
                    $showMethod = 'showContent';
                    $content = new Content($this->dbConnection);
                    
                    if(isset($_POST['title']) && strlen($_POST['title']) > 0 && isset($_POST['titleen']) && strlen($_POST['titleen']) > 0 && isset($_POST['contenten']) && strlen($_POST['contenten']) > 0 && isset($_POST['content']) && strlen($_POST['content']) > 0 && isset($_POST['pageId']) && strlen($_POST['pageId']) > 0) {                        
                        $id = (int) $_POST['id'];
                        if(isset($id) && $id > 0) {
                            $content->setContent($_POST['title'], $_POST['titleen'], $_POST['content'], $_POST['contenten'], $this->user->getId(), $_POST['pageId'], $id);
                            $content->update();                            
                        } else {
                            $content->setContent($_POST['title'], $_POST['titleen'], $_POST['content'], $_POST['contenten'], $this->user->getId(), $_POST['pageId']);
                            $content->insert();
                        }
                        header('Location: admin.php?do=editContent');
                    } elseif(isset($_GET['del'])) {
                        $content->setContentById($_GET['del']);
                        $content->delete();
                    } elseif(isset($_GET['edit'])) {
                        $content->setContentById($_GET['edit']);
                    }
                    
                    $form = new Form($this->prepareInputs('content', $content, $this->dbConnection->selectPages()), '', 'POST');
                    $headerTitle = 'Zarządzaj treścią';
                    $panel->setForm($form->createForm());
                    $panel->setContent('content', $this->dbConnection->selectContent());
                    break;
                    
                case 'editPages':
                    $showMethod = 'showPages';
                    $page = new Page($this->dbConnection);
                    
                    if(isset($_POST['name']) && strlen($_POST['name']) > 0 && isset($_POST['nameen']) && strlen($_POST['nameen']) > 0 && isset($_POST['sectionId']) && strlen($_POST['sectionId']) > 0 && is_numeric($_POST['sectionId'])) {
                        $id = (int) $_POST['id'];
                        if(isset($id) && $id > 0) {
                            $page->setPage($_POST['name'],$_POST['nameen'], $_POST['sectionId'], $id);
                            $page->update();
                        } else {
                            $page->setPage($_POST['name'],$_POST['nameen'], $_POST['sectionId']);
                            $page->insert();
                        }
                        header('Location: admin.php?do=editPages');
                    } elseif(isset($_GET['del'])) {
                        $page->setPageById($_GET['del']);
                        $page->delete();
                    } elseif(isset($_GET['edit'])) {
                        $page->setPageById($_GET['edit']);
                    }
                    
                    $headerTitle = 'Zarządzaj podstronami';
                    $form = new Form($this->prepareInputs('page', $page, $this->dbConnection->selectCategories()), '', 'POST');
                    $panel->setForm($form->createForm());
                    $panel->setContent('page', $this->dbConnection->selectPages());
                    break;
                    
                case 'editMenu':
                    $showMethod = 'showMenu';                    
                    $menu = new Menu($this->dbConnection);
                    
                    if(isset($_POST['name']) && strlen($_POST['name']) > 0 && isset($_POST['nameen']) && strlen($_POST['nameen']) > 0 && isset($_POST['address']) && strlen($_POST['address']) > 0 && isset($_POST['isMenu']) && ($_POST['isMenu'] == 0 || $_POST['isMenu'] == 1) && isset($_POST['order']) && strlen($_POST['order']) > 0 && is_numeric($_POST['order']) && $_POST['order'] >=0) {
                        $id = (int) $_POST['id'];
                        if(isset($id) && $id > 0) {
                            $menu->setMenu($_POST['name'], $_POST['nameen'], $_POST['address'], $_POST['isMenu'], $_POST['order'], $id);
                            $menu->update();
                        } else {
                            $menu->setMenu($_POST['name'], $_POST['nameen'], $_POST['address'], $_POST['isMenu'], $_POST['order']);
                            $menu->insert();
                        }
                        header('Location: admin.php?do=editMenu');
                    } elseif(isset($_GET['del'])) {
                        $menu->setMenuById($_GET['del']);
                        $menu->delete();
                    } elseif(isset($_GET['edit'])) {
                        $menu->setMenuById($_GET['edit']);
                    }
                    
                    $form = new Form($this->prepareInputs('menu', $menu), '', 'POST');
                    $headerTitle = 'Zarządzaj menu';
                    $panel->setForm($form->createForm());
                    $panel->setContent('menu', $this->dbConnection->selectMenu());
                    break;
                    
                case 'editQuiz':
                    $showMethod = 'showQuiz';
                    $question = new Question($this->dbConnection);
                    
                    if(isset($_POST['content']) && strlen($_POST['content']) > 0 && isset($_POST['contenten']) && strlen($_POST['contenten']) > 0 && isset($_POST['answer1en']) && strlen($_POST['answer1en']) > 0 && isset($_POST['answer2en']) && strlen($_POST['answer2en']) > 0 && isset($_POST['answer1']) && strlen($_POST['answer1']) > 0 && isset($_POST['answer2']) && strlen($_POST['answer2']) > 0) {
                        $id = (int) $_POST['id'];
                         if(isset($id) && $id > 0) {
                            $question->setQuestion($_POST['content'], $_POST['contenten'], $_POST['answer1'], $_POST['answer1en'], $_POST['answer2'], $_POST['answer2en'], $id);
                            $question->update();
                         } else {
                             $question->setQuestion($_POST['content'], $_POST['contenten'], $_POST['answer1'], $_POST['answer1en'], $_POST['answer2'], $_POST['answer2en']);
                             $question->insert();
                         }
                         header('Location: admin.php?do=editQuiz');
                    } elseif(isset($_GET['del'])) {
                        $question->setQuestionById($_GET['del']);
                        $question->delete();
                    } elseif(isset($_GET['edit'])) {
                        $question->setQuestionById($_GET['edit']);
                    }
                    
                    $form = new Form($this->prepareInputs('question', $question), '', 'POST');
                    $headerTitle = 'Zarządzaj treścią quizu';
                    $panel->setForm($form->createForm());
                    $panel->setContent('question', $this->dbConnection->selectQuestions());
                    break;
                    
                default: 
                    $headerTitle = 'Panel Administratora';
                    $panel->content();
                    break;
            }
        } catch(ContentException $ex) {
            $panel->setError('Żadna treść nie może być znaleziona w oparciu o wprowadzone dane.');
        } catch(NoDataException $ex) {
            $panel->setError('Brak treści','Brak elementów spełniających podane kryteria.');
        } catch(PDOException $ex) {
            $panel->setError('Błąd #2.', 'Skontaktuj się z Administratorem.');
        } catch(WrongIDException $ex) {
            $panel->setError('Błąd', 'Brak treści o podanym ID.');
        } catch(UpdateException $ex) {
            $panel->setError('Błąd #3', 'Skontaktuj się z Administratorem');
        } finally {
            $navigation = new Navigation($this->dbConnection->selectPages(), $this->dbConnection->selectMenu());
            $panel->header($headerTitle, $navigation->getNav());
            $panel->$showMethod();
            $panel->footer();
        }
    }
    
    
    /**
     * Method that prepares array that includes all parameters for HTML inputs (text, textarea and select).
     * 
     * @param string
     * @param prepared object
     * @param SQL query
     * @return array
     */
    public function prepareInputs($type, $object, $params = null) {
        if($type == 'menu') {
            $array = array(
                array('type' => 'text', 'label' => 'ID: ', 'name' => 'id', 'id'=>'id', 'readonly'=>'true', 'value'=>$object->getId()),
                array('type' => 'text', 'label' => 'Nazwa: ', 'name' => 'name', 'id'=>'name', 'value'=>$object->getName()),
                array('type' => 'text', 'label' => 'Nazwa en: ', 'name' => 'nameen', 'id'=>'nameen', 'value'=>$object->getNameen()),
                array('type' => 'text', 'label' => 'Adres: ', 'name' => 'address', 'id'=>'address', 'value'=>$object->getAddress()),
                array('type' => 'select', 'label' => 'Typ: ', 'name' => 'isMenu', 'id'=>'isMenu', 'options'=>array(
                    '0'=>array('value'=>'Menu'), 
                    '1'=>array('value'=>'Kategoria'),
                )),
                array('type' => 'text', 'label' => 'Kolejność: ', 'name' => 'order', 'id'=>'order', 'value'=>$object->getOrder()),
            );
            $array[3]['options'][$object->getIsMenu()]['selected'] = 'selected';
            return $array;
            
        } elseif($type == 'page') {
            $options = array();
            while($opt = $params->fetch()) {
                $options[$opt['id']] = array('value' => $opt['name']);
             }
             if($object->getSectionId() > 0)
                $options[$object->getSectionId()]['selected'] = 'selected';
            return array(
                array('type' => 'text', 'label' => 'ID: ', 'name' => 'id', 'id'=>'id', 'readonly'=>'true', 'value'=>$object->getId()),
                array('type' => 'text', 'label' => 'Nazwa: ', 'name' => 'name', 'id'=>'name', 'value'=>$object->getName()),
                array('type' => 'text', 'label' => 'Nazwa en: ', 'name' => 'nameen', 'id'=>'nameen', 'value'=>$object->getNameen()),
                array('type' => 'select', 'label' => 'Nazwa menu: ', 'name' => 'sectionId', 'id'=>'sectionId', 'options'=>$options),
            );
            
        } elseif($type == 'content') {
            $options = array();
                while($opt = $params->fetch()) {
                    $options[$opt["id"]] = array('value' => $opt["name"]);                    
                }
                if ($object->getPageId() > 0) {
                    $options[$object->getPageId()]['selected'] = 'selected';
                }
            return array(                
                array('type' => 'text', 'label' => 'ID: ', 'name' => 'id', 'id'=>'id', 'readonly'=>'true', 'value'=>$object->getId()),
                array('type' => 'text', 'label' => 'Tytuł: ', 'name' => 'title', 'id'=>'title', 'value'=>$object->getTitle()),
                array('type' => 'text', 'label' => 'Tytuł en: ', 'name' => 'titleen', 'id'=>'titleen', 'value'=>$object->getTitleen()),
                array('type' => 'textarea', 'label' => 'Treść: ', 'name' => 'content', 'id'=>'content', 'value'=>$object->getContent()),
                array('type' => 'textarea', 'label' => 'Treść en: ', 'name' => 'contenten', 'id'=>'contenten', 'value'=>$object->getContenten()),
                array('type' => 'select', 'label' => 'Nazwa podstrony: ', 'name' => 'pageId', 'id'=>'pageId', 'options'=>$options),                
            );
            
        } elseif($type == 'question') {
            return array(
                array('type' => 'text', 'label' => 'ID: ', 'name' => 'id', 'id'=>'id', 'readonly'=>'true', 'value'=>$object->getId()),
                array('type' => 'textarea', 'label' => 'Treść: ', 'name' => 'content', 'id'=>'content', 'value'=>$object->getContent()),
                array('type' => 'textarea', 'label' => 'Treść en: ', 'name' => 'contenten', 'id'=>'contenten', 'value'=>$object->getContenten()),
                array('type' => 'text', 'label' => 'Odpowiedź 1: ', 'name' => 'answer1', 'id'=>'answer1', 'value'=>$object->getAnswer1()),
                array('type' => 'text', 'label' => 'Odpowiedź 1 en: ', 'name' => 'answer1en', 'id'=>'answer1en', 'value'=>$object->getAnswer1en()),
                array('type' => 'text', 'label' => 'Odpowiedź 2: ', 'name' => 'answer2', 'id'=>'answer2', 'value'=>$object->getAnswer2()),
                array('type' => 'text', 'label' => 'Odpowiedź 2 en: ', 'name' => 'answer2en', 'id'=>'answer2en', 'value'=>$object->getAnswer2en()),
            );
            
        }        
    }
    
    
    /**
     * Method chcecks if any user is currently connected.
     * 
     * @return int
     */
    private function sessionCheck() {
        if(isset($_SESSION['userId'])){
            return $_SESSION['userId'];
        } else {
            return 0;
        }
    }
    
    
    /**
     * Method that saves UserID parameter in session.
     * 
     * @param int
     */
    private function setSession($id) {
        $_SESSION['userId'] = $id;
    }
}