<?php
require_once('PageView.php');
require_once('Form.php');

/**
 * 
 * @author Bartosz Studnik
 */
class QuizView extends PageView  {

    /**
     * @var string 
     */
    private $form;    
    
    
    
    /**
     * Constrictor with no parameters. It calls parent class' constructor.
     */
    public function __construct() {

        parent::__construct();
    }
    
    
    /**
     * Method prints main content on website.
     * 
     * @param string
     */
    public function content($content = null) {
        ?>
                <header>
                    <h1>Quiz</h1>
                </header>
                <article>
                    <p><?=$content;?></p>
                </article>
        <?php
    }
    
    
    /**
     * Method prints questions on website.
     * 
     * @param int
     * @param string
     * @param string
     * @param string
     * @param string
     */
    public function showQuiz($num, $answer1, $answer2, $content = null, $form = '') {
        ?>
                <header>
                    <h1><?=QUESTION;?> #<?=$num;?></h1>
                </header>
                <article>
                    <p style="text-align: center"><?=$content;?><br /></p>
                    <div id="quizWrapper">
                        <div class="quiz1"><img class="quizImg" src="../media/images/quiz_images/<?=$answer1;?>"></div>
                        <div class="quiz2"><img class="quizImg" src="../media/images/quiz_images/<?=$answer2;?>"></div>
                    </div>
                    <?=$form;?>
                </article>
        <?php
    }
    
    
    /**
     * Opens HTML form tag
     */
    public function startForm() {
        ?>
            <form method="POST">
     <?php
    }
    
    
    /**
     * Closes HTML form tag
     */
    public function endForm() {
        ?>
                <input type="submit" value="<?=SUBMIT_TEXT;?>" style="margin-left: 50px"></form>
     <?php
    }
    
    
    
    public function setForm($form) {
        $this->form = $form;
    }
}