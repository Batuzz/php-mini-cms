<?php
require_once ('exceptions/ContentException.php');
require_once('exceptions/WrongIDException.php');

/**
 * Class that manages main content on the webste.
 * 
 * @author Bartosz Studnik
 */
class Content {
    
    /**
     * @var Database Connection
     */
    private $dbConnection;
    
    /**
     * Article ID
     * @var int
     */
    private $id;
    
    /**
     * Article title
     * @var string
     */
    private $title;
    
    /**
     * English article title
     * @var string
     */
    private $titleen;
    
    /**
     * Article content
     * @var string
     */
    private $content;
    
    /**
     * English article content
     * @var string
     */
    private $contenten;
    
    /**
     * Article addition date
     * @var int
     */
    private $date;
    
    /**
     * Article author ID
     * @var int
     */
    private $authorid;
    
    /**
     * Article page ID
     * @var int
     */
    private $pageid;
    
    
    /**
     * Constructor with one parameter. Connects with Database.
     * 
     * @param Database connection
     */
    public function __construct($db) {
        
        $this->dbConnection = $db->getConnection();
        $this->id = '';
        $this->title = '';
        $this->titleen = '';
        $this->content = '';
        $this->contenten = '';
        $this->date = '';
        $this->authorid = '';
        $this->pageid = '';
    }
    
    
    /**
     * Sets current article data.
     * 
     * @param string
     * @param string
     * @param int
     * @param int
     * @param int
     */
    public function setContent($title, $titleen, $content, $contenten, $authorid, $pageid, $id = 0) {
        
        $this->id = $id;
        $this->title = $title;
        $this->titleen = $titleen;
        $this->content = $content;
        $this->contenten = $contenten;
        $this->date = date('Y-m-d H:i:s');
        $this->authorid = $authorid;
        $this->pageid = $pageid;
    }
    
    
    /**
     * Method inserts current content into Database.
     * Content needs to be set before.
     */
    public function insert() {
        
        $query = $this->dbConnection->prepare("INSERT INTO `contents` (`id`, `title`, `titleen`, `content`, `contenten`, `date`, `authorid`, `pageid`) VALUES (NULL, :title, :titleen, :content, :contenten, :date, :authorid, :pageid)");
        $query->bindValue(':title', $this->title, PDO::PARAM_STR);
        $query->bindValue(':titleen', $this->titleen, PDO::PARAM_STR);
        $query->bindValue(':content', $this->content, PDO::PARAM_STR);
        $query->bindValue(':contenten', $this->contenten, PDO::PARAM_STR);
        $query->bindValue(':date', $this->date, PDO::PARAM_INT);
        $query->bindValue(':authorid', $this->authorid, PDO::PARAM_INT);
        $query->bindValue(':pageid', $this->pageid, PDO::PARAM_INT);
        $query->execute();
        $this->id = $this->dbConnection->lastInsertId();
    }
    
    
    /**
     * Method updates current content.
     * 
     * @throws UpdateException when `Article ID` is wrong.
     */
    public function update() {
        
        if($this->id <=0) {
            throw new UpdateException();
        }
        $query = $this->dbConnection->prepare('UPDATE `contents` SET `title` =:title, `titleen` =:titleen, `content`=:content, `contenten`=:contenten, `date` =:date, `authorid` =:authorid, `pageid` =:pageid WHERE id = :id');
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':title', $this->title, PDO::PARAM_STR);
        $query->bindValue(':titleen', $this->titleen, PDO::PARAM_STR);
        $query->bindValue(':content', $this->content, PDO::PARAM_STR);
        $query->bindValue(':contenten', $this->contenten, PDO::PARAM_STR);
        $query->bindValue(':date', $this->date, PDO::PARAM_INT);
        $query->bindValue(':authorid', $this->authorid, PDO::PARAM_INT);
        $query->bindValue(':pageid', $this->pageid, PDO::PARAM_INT);
        $query->execute();
        $this->id = $this->dbConnection->lastInsertId();
    }
    
    
    /**
     * Method selects whole content by `Article ID`.
     * 
     * @param int
     * @throws WrongIDException when there is no `Article` content matched with 'Article ID`
     */
    public function setContentById($id) {
        
        $query = $this->dbConnection->prepare('SELECT `id`, `title`, `titleen`, `content`, `contenten`, `date`, `authorid`, `pageid` FROM `contents` WHERE `id` = :id');
        $query->bindValue(':id',$id, PDO::PARAM_INT);
        $query->execute();
        $rows = $query->rowCount();
        if ($rows <= 0) {
            throw new WrongIDException();
        }
        $result = $query->fetch();
        $this->id = $result['id'];
        
        $this->title = $result['title'];
        $this->titleen = $result['titleen'];
        $this->content = $result['content'];
        $this->contenten = $result['contenten'];
        $this->date = $result['date'];
        $this->author_id = $result['authorid'];
        $this->pageid = $result['pageid'];
    }
    
    
    /**
     * Method deletes current content.
     * 
     * @throws DeleteException when `Article ID` is wrong.
     */
    public function delete() {
        
        if($this->id <=0) {
            throw new DeleteException();
        }
        $query = $this->dbConnection->prepare('DELETE FROM `contents` where `id`= :id');
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->execute();
        $this->id = '';
        $this->title = '';
        $this->titleen = '';
        $this->content = '';
        $this->contenten = '';
        $this->date = '';
        $this->authorid = '';
        $this->pageid = '';
    }   
    
    
    
    public function getId() {
        return $this->id;
    }
    
    public function getTitleLang() {
        if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'en') {
            return $this->titleen;
        } else {
            return $this->title;
        }        
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function getTitleen() {
        return $this->titleen;
    }
    
    public function getContentLang() {
        if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'en') {
            return $this->contenten;
        } else {
            return $this->content;
        }        
    }
    
    public function getContent() {
        return $this->content;
    }
    
    public function getContenten() {
        return $this->contenten;
    }
    
    public function getDate() {
        return $this->date;
    }
    
    public function getAuthorId() {
        return $this->authorid;
    }
    
    public function getPageId() {
        return $this->pageid;
    }
}
