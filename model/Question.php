<?php

/**
 * Class that manages Questions section
 *
 * @author Bartosz Studnik
 */
class Question {
    
    /**
     * @var Database Connection
     */
    private $dbConnection;
    
    /**
     * Question ID
     * @var int
     */
    private $id;
    
    /**
     * Question content
     * @var string
     */
    private $content;
    
    /**
     * English question content
     * @var string
     */
    private $contenten;
    
    /**
     * Answer1 description
     * @var string
     */
    private $answer1;
    
    /**
     * Answer1 description
     * @var string
     */
    private $answer1en;
    
    /**
     * Answer2 description
     * @var string
     */
    private $answer2;
    
    /**
     * Answer2 description
     * @var string
     */
    private $answer2en;
    
    
    
    /**
     * Constructor with one parameter. Connects with Database.
     * 
     * @param Database Connection
     */
    public function __construct($dbConnection){
        $this->dbConnection = $dbConnection->getConnection();
        $this->id = '';
        $this->content = '';
        $this->contenten = '';
        $this->answer1 = '';
        $this->answer1en = '';
        $this->answer2 = '';
        $this->answer2en = '';
    }
    
    
    /**
     * Sets current question data.
     * 
     * @param string
     * @param string
     * @param string
     */
    public function setQuestion($content, $contenten, $answer1, $answer1en, $answer2, $answer2en, $id = 0) {
        
        $this->id = $id;
        $this->content = $content;
        $this->contenten = $contenten;
        $this->answer1 = $answer1;
        $this->answer1en = $answer1en;
        $this->answer2 = $answer2;
        $this->answer2en = $answer2en;
    }
    
    
    /**
     * Method inserts current question into Database.
     * Question needs to be set before.
     */
    public function insert() {
        
        $query = $this->dbConnection->prepare("INSERT INTO `questions` (`id`, `content`, `contenten`, `answer1`, `answer1en`, `answer2`, `answer2en`) VALUES (NULL, :content, :contenten, :answer1, :answer1en, :answer2, :answer2en)");
        $query->bindValue(':content', $this->content, PDO::PARAM_STR);
        $query->bindValue(':contenten', $this->contenten, PDO::PARAM_STR);
        $query->bindValue(':answer1', $this->answer1, PDO::PARAM_STR);
        $query->bindValue(':answer1en', $this->answer1en, PDO::PARAM_STR);
        $query->bindValue(':answer2', $this->answer2, PDO::PARAM_STR);
        $query->bindValue(':answer2en', $this->answer2en, PDO::PARAM_STR);
        $query->execute();
        $this->id = $this->dbConnection->lastInsertId();
    }
    
    
    /**
     * Method selects whole question by `Question ID`.
     * 
     * @param int
     * @throws WrongIDException when there is no `Question` matched with `Question ID`
     */
    public function setQuestionById($id) {
        
        $query = $this->dbConnection->prepare('SELECT `id`, `content`, `contenten`, `answer1`, `answer1en`, `answer2`, `answer2en` FROM `questions` WHERE `id` = :id');
        $query->bindValue(':id',$id, PDO::PARAM_INT);
        $query->execute();
        $rows = $query->rowCount();
        if ($rows <= 0) {
            throw new WrongIDException();
        }
        $result = $query->fetch();
        $this->id = $result['id'];
        $this->content = $result['content'];
        $this->contenten = $result['contenten'];
        $this->answer1 = $result['answer1'];
        $this->answer1en = $result['answer1en'];
        $this->answer2 = $result['answer2'];
        $this->answer2en = $result['answer2en'];
    }
    
    
    /**
     * Method updates current question.
     * 
     * @throws UpdateException when `Question ID` is wrong.
     */
    public function update() {
        
        if($this->id <=0) {
            throw new UpdateException();
        }
        $query = $this->dbConnection->prepare('UPDATE `questions` SET `content` = :content, `contenten` = :contenten, `answer1` = :answer1, `answer1en` = :answer1en, `answer2` = :answer2, `answer2en` = :answer2en WHERE id = :id');
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':content', $this->content, PDO::PARAM_STR);
        $query->bindValue(':contenten', $this->contenten, PDO::PARAM_STR);
        $query->bindValue(':answer1', $this->answer1, PDO::PARAM_STR);
        $query->bindValue(':answer1en', $this->answer1en, PDO::PARAM_STR);
        $query->bindValue(':answer2', $this->answer2, PDO::PARAM_STR);
        $query->bindValue(':answer2en', $this->answer2en, PDO::PARAM_STR);
        $query->execute();
        $this->id = $this->dbConnection->lastInsertId();
    }   
    
    
    /**
     * Method deletes current question.
     * 
     * @throws DeleteException when `Question ID` is wrong.
     */
    public function delete() {        
        if($this->id <=0) {
            throw new DeleteException();
        }
        $query = $this->dbConnection->prepare('DELETE FROM `questions` where `id`= :id');
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->execute();
        $this->id = '';
        $this->content = '';
        $this->contenten = '';
        $this->answer1 = '';
        $this->answer1en = '';
        $this->answer2 = '';
        $this->answer2en = '';
    }  
    
    
    
    public function getId(){
        return $this->id;
    }
    
    public function getContent(){
        return $this->content;
    }
    
    public function getContenten(){
        return $this->contenten;
    }
    
    public function getAnswer1(){
        return $this->answer1;
    }
    
    public function getAnswer1en(){
        return $this->answer1en;
    }
    
    public function getAnswer2(){
        return $this->answer2;
    }
    
    public function getAnswer2en(){
        return $this->answer2en;
    }
}