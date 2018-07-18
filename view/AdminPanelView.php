<?php
require_once('PageView.php');
require_once('Form.php');
require_once('exceptions/WrongIDException.php');

/**
 * @author Bartosz Studnik
 */
class AdminPanelView extends PageView {
   
    /**
     * Input type
     * @var string 
     */
    private $type;
    
    /**
     * @var string
     */
    private $content;
    
    /**
     * @var form (object) 
     */
    private $form;
    
    
    /**
     * Constructor with one parameter. Sets input types.
     * 
     * @param string
     */
    public function __construct($type = '') {
        
        parent::__construct();
        $this->type = $type;
        $this->content = '';
        $this->form = '';
        $specialContent = '<table class="admin">
                <tr><th>Panel Administratora</th></tr>
                <tr><td><a href="?do=editUsers">Użytkownicy</a></td></tr>
                <tr><td><a href="?do=editMenu">Menu</a></td></tr>
                <tr><td><a href="?do=editPages">Podstrony</a></td></tr> 
                <tr><td><a href="?do=editContent">Treści</a></td></tr>
                <tr><td><a href="?do=editQuiz">Quiz</a></td></tr>                               
            </table>';
        $this->setSpecialContent($specialContent);
    }
    
    
    /**
     * Method prints default content when no PanelAdmin option was chosen.
     */
    public function content($content = null) {
        ?>
                <header>
                    <h1>Panel Administratora</h1>
                </header>
                <article>
                    <p>Witaj w Panelu Administratora. Wybierz jedną z opcji po lewej stronie.</p>
                </article>
        <?php
    }
    
    
    /**
     * Method that prints all users info in the table on the website.
     * 
     * @param usersQuery
     */
    public function showUsers() {
        ?>
            <header>
                <h1>Użytkownicy</h1>
            </header>
            <article>
                <?php if(!$this->error) {?>
                <article>
                    <table class="optTable" border="1">
                        <tr><th>ID</th><th>Nazwa użytkownika</th><th>Data rejestracji</th><th>Poziom dostępu</th></tr>
                    <?=$this->content;?>
                    </table>
                    <?php } else { ?>
                    <?=$this->error;?>
                </article>
            </article>
                <?php
                }
    }
    
    
    /**
     * Method that prints all contents info in the table on the website.
     * 
     * @param contentsQuery
     */
    public function showContent() {
        ?>
                <header>
                    <h1>Zarządzaj treścią</h1>
                </header>
                <article><?=$this->form;?>
                <?php if(!$this->error) {?>
                        <table class="optTable" border="1">
                            <tr><th>ID</td><th>Tytuł</th><th>Tytuł en</th><th>Treść</th><th>Treść en</th><th>Data</th><th>ID autora</th><th>Nazwa strony</th><th>Usuń</th><th>Edytuj</th></tr>
                            <?=$this->content;?>
                        </table>
                    </article>
                <?php } else { ?>
                    <?=$this->error;?>
                </article>
        <?php
                }
    }
    
    
    /**
     * Method that prints all pages info in the table on the website.
     */
    public function showPages() {
        ?>
                <header>
                    <h1>Podstrony</h1>
                </header>
                <article><?=$this->form;?>
                <?php if(!$this->error) {?>
                        <table class="optTable" border="1">
                            <tr><th>ID</th><th>Nazwa</th><th>Nazwa en</th><th>Nazwa sekcji</th><th>Usuń</th><th>Edytuj</th></tr>
                            <?=$this->content;?>
                        </table>
                    </article>
                <?php } else { ?>
                    <?=$this->error;?>
                </article>
        <?php
                }
    }
    
    
    /**
     * Method that prints all Quiz info in the table on the website.
     */
    public function showQuiz() {
        ?>
                <header>
                    <h1>Quiz</h1>
                </header>
                <article><?=$this->form;?>
                <?php if(!$this->error) {?>
                        <table class="optTable" border="1">
                            <tr><th>ID</th><th>Treść</th><th>Treść en</th><th>Odpowiedź #1</th><th>Odpowiedź #1 en</th><th>Odpowiedź #2</th><th>Odpowiedź #2 en</th><th>Usuń</th><th>Edytuj</th></tr>
                            <?=$this->content;?>
                        </table>
                <?php } else { ?>
                    <?=$this->error;?>
                </article>
        <?php
                }
    }
    
    
    /**
     * Method that prints all menu info in the table on the website.
     * 
     * @param menuQuery
     */
    public function showMenu() {
        ?>
                <header>
                    <h1>Menu</h1>
                </header>
                <article><?=$this->form;?>
                <?php if(!$this->error) {?>
                        <table class="optTable" border="1">
                            <tr><th>ID</th><th>Nazwa</th><th>Nazwa en</th><th>Adres</th><th>Kategoria</th><th>Kolejność</th><th>Usuń</th><th>Edytuj</th></tr>
                        <?=$this->content;?>
                        </table>
                <?php } else { ?>
                    <?=$this->error;?>
                </article>
        <?php
                }
    }
    
    
    /**
     * Method that initlizes current content to be displayed.
     * 
     * @param string
     * @param contentQuery
     */
    public function setContent($type, $content) {
        if($type == 'menu'){
            while($menu = $content->fetch()) {
                $this->content .= '<tr><td>'.$menu['id'].'</td><td>'.$menu['name'].'</td><td>'.$menu['nameen'].'</td><td>'.$menu['address'].'</td><td>'.$menu['isMenu'].'</td><td>'.$menu['order'].'</td><td><a href="?do=editMenu&del='.$menu['id'].'"><div class="delImg"></div></a></td><td><a href="?do=editMenu&edit='.$menu['id'].'"><div class="editImg"></div></a></td></tr>';        
            }
        } elseif($type == 'page') {
            while($page = $content->fetch()) {
                $this->content .= '<tr><td>'.$page['id'].'</td><td>'.$page['name'].'</td><td>'.$page['nameen'].'</td><td>'.$page['menuName'].'</td><td><a href="?do=editPages&del='.$page['id'].'"><div class="delImg"></div></a></td><td><a href="?do=editPages&edit='.$page['id'].'"><div class="editImg"></div></a></td></tr>';
            }
        } elseif ($type == 'content') {
            while($contents = $content->fetch()) {
                $this->content .= '<tr><td>'.$contents['id'].'</td><td>'.$contents['title'].'</td><td>'.$contents['titleen'].'</td><td>'.$contents['content'].'</td><td>'.$contents['contenten'].'</td><td>'.$contents['date'].'</td><td>'.$contents['authorid'].'</td><td>'.$contents['pageName'].'</td><td><a href="?do=editContent&del='.$contents['id'].'"><div class="delImg"></div></a></td><td><a href="?do=editContent&edit='.$contents['id'].'"><div class="editImg"></div></a></td></tr>';
            }
        } elseif ($type == 'question') {
            while($quiz = $content->fetch()) {
                $this->content .= '<tr><td>'.$quiz['id'].'</td><td>'.$quiz['content'].'</td><td>'.$quiz['contenten'].'</td><td>'.$quiz['answer1'].'</td><td>'.$quiz['answer1en'].'</td><td>'.$quiz['answer2'].'</td><td>'.$quiz['answer2en'].'</td><td><a href="?do=editQuiz&del='.$quiz['id'].'"><div class="delImg"></div></a></td><td><a href="?do=editQuiz&edit='.$quiz['id'].'"><div class="editImg"></div></a></td> </tr>';
            }
        } elseif ($type == 'user') {
            while($user = $content->fetch()) {
                $this->content .= '<tr><td>'.$user['id'].'</td><td>'.$user['name'].'</td><td>'.$user['date'].'</td><td>'.$user['access'].'</td></tr>';
            }
        }
    }
    
    
    /**
     * Method that initializes current form.
     * 
     * @param string
     */
    public function setForm($form) {
        $this->form = $form;
    }
}