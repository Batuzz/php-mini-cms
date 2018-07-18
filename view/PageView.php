<?php
session_start();
if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'en') {
    require_once('language/en.php');
} else {
    require_once('language/pl.php');
}

/**
 * Abstract class that methods are called form inheriting class.
 * It contains main website's HTML code.
 * It starts a session.
 * It loads proper language constants file.
 *
 * @author Bartosz Studnik
 */
abstract class PageView {
    
    /**
     * @var string 
     */
    private $specialContent;
    
    /**
     * @var string 
     */
    protected $error_title;
    
    /**
     * @var string
     */
    protected $error;
    
    
    
    /**
     * Constructor with no parameters. Initializes `specialContent` as empty string.
     */
    public function __construct() {

        $this->specialContent = '';
    }
    
    
    /**
     * Method that prints header and main body of website.
     * 
     * @param string
     */
    public function header($main_title = 'e-parchment.net', $nav) {
        ?>
        <!DOCTYPE HTML>
        <html lang="pl">
        <head>
            <meta charset="utf-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

            <title><?=$main_title;?></title>

            <meta name="description" content="E-book VS książka, list VS e-mail! Kto zwycięży? Dowiedz się tego u nas!" />
            <meta name="keywords" content="e-parchment, e, parchment, konkurs, dokumenty, pergamin, documents, rybnik, zory, miroslaw, szarama, bartosz, studnik" />

            <link rel="stylesheet" href="http://e-parchment.net/media/css/style.css" type="text/css" />
            
            <link href='https://fonts.googleapis.com/css?family=Caveat:400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
            <link href='https://fonts.googleapis.com/css?family=Black+Ops+One' rel='stylesheet' type='text/css'>
        </head>

        <body>
            <div class="topbar">
                <div id="logo">
                        <a href="index.php">e-parchment.net</a>
                </div>
                <nav>
                    <ol>
                        <?=$this->createNav($nav);?>
                    </ol>
                </nav>
            </div>
            <?=$this->getSpecialContent();?>
            <section>
        <?php
    }
    
    
    /**
     * Method that creates menu for current site.
     * 
     * @param navigation
     * @return string
     */
    private function createNav($nav) {
        $toReturn = '';
        $lang = '';
        if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'en') {
            $lang = 'nameen';
        } else {
            $lang = 'name';
        }  
        foreach($nav as $e) {
            if($e["isMenu"] == 0) {
                $toReturn .= '<a href="'.$e["address"].'">
                            <li>'.$e[$lang].'</li>
                    </a>';
            }
            else {
                $toReturn .= '<li>'.$e[$lang].'
                            <ul>';
                foreach($e["pages"] as $e2) {
                    $toReturn .= '<li><a class="drop" href="index.php?pageId='.$e2["id"].'">'.$e2[$lang].'.html</a></li>';
                }
                $toReturn .= '</ul>
                        </li>';
            }
        }
        return $toReturn;
    }
    
    
    /**
     * Method that prints footer of website.
     */
    public function footer() {
        ?>
            </section>
                <footer>
                    e-parchment.net &copy; <?=FOOTER_RIGHTS;?>
                    <h6>
                    <?=FOOTER_TEXT;?><br />
                    <?=FOOTER_ORGANIZER;?>
                    </h6>
                    <span style="color: green;">
                        <a href="lang=pl">
                            <div class="langPl"></div>
                        </a>
                        <a href="lang=en">
                            <div class="langEn"></div>
                        </a>
                    </span>
                </footer>
        </body>
        </html>
        <?php
    }
    
    
    /**
     * Method that prints error on website.
     * 
     * @param string
     * @param string
     */
     public function showError() {
        ?>
            <header>
                <h1><?=$this->error_title;?></h1>
            </header>
            <article>
                <?= $this->error;?>
            </article>
        <?php
    }
    
    
    /**
     * Method that prints note on website.
     * 
     * @param string
     * @param string
     */
     public function showNote($title, $message) {
        ?>
            <header>
                    <h1><?=$title;?></h1>
            </header>
            <article>
                <?=$message;?>
            </article>
        <?php
    }
    
    
    /**
     * Method that prepares error.
     * 
     * @param string
     * @param string
     */
    public function setError($error_title, $error) {
        $this->error_title = $error_title;
        $this->error = $error;
    }
    
    
    /**
     * Method that prepares specialContent.
     * 
     * @param string
     */
    public function setSpecialContent($specialContent) {
        
        $this->specialContent = $specialContent;
    }
    
    
    /**
     * Abstract method to remind about setting content to each type of sub-page.
     */
    abstract public function content($content = null);
    
    
    
    public function getSpecialContent() {
        return $this->specialContent;
    }
}