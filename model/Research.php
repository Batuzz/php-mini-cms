<?php

/**
 * Class that manages Research section
 *
 * @author Bartosz Studnik
 */
class Research {
    
     /**
     * @var Database Connection
     */
    private $dbConnection;
    
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var string
     */
    private $gender;
    
    /**
     * @var string
     */
    private $nickname;
    
    /**
     * @var int
     */
    private $age;
    
    /**
     * @var int
     */
    private $questionid;
    
    /**
     * @var string 
     */
    private $answer;
    
    
    
    /**
     * Constructor with one parameter. Connects with Database.
     * 
     * @param Database connection
     */
    public function __construct($db) {
        
        $this->dbConnection = $db->getConnection();
        $this->id = '';
        $this->gender = '';
        $this->nickname = '';
        $this->age = '';
        $this->questionid = '';
        $this->answer = '';
    }
    
    
    /**
     * Sets current answer data.
     * 
     * @param string
     * @param string
     * @param int
     * @param int
     * @param int
     */
    public function setResearch($gender, $age, $nickname, $questionid, $answer, $id = 0) {
        
        $this->id = $id;
        $this->gender = $gender;
        $this->age = $age;
        $this->nickname = $nickname;
        $this->answer = $answer;
        $this->questionid = $questionid;
    }
    
    
    /**
     * Method inserts current content into Database.
     * Content needs to be set before.
     */
    public function insert() {
        
        $query = $this->dbConnection->prepare("INSERT INTO `research` (`id`, `nickname`, `age`, `gender`, `questionid`, `answer`) VALUES (NULL, :nickname, :age, :gender, :questionid, :answer)");
        $query->bindValue(':nickname', $this->nickname, PDO::PARAM_STR);
        $query->bindValue(':age', $this->age, PDO::PARAM_INT);
        $query->bindValue(':gender', $this->gender, PDO::PARAM_STR);
        $query->bindValue(':questionid', $this->questionid, PDO::PARAM_INT);
        $query->bindValue(':answer', $this->answer, PDO::PARAM_STR);
        $query->execute();
        $this->id = $this->dbConnection->lastInsertId();
    }
    
    
    /**
     * Method updates current answer.
     * 
     * @throws UpdateException when `Research ID` is wrong.
     */
    public function update() {
        
        if($this->id <=0) {
            throw new UpdateException();
        }
        $query = $this->dbConnection->prepare('UPDATE `contents` SET `nickname` = :nickname, `age` = :age, `gender` = :gender, `questionid` = :questionid, `answer` = :answer WHERE id = :id');
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':nickname', $this->nickname, PDO::PARAM_STR);
        $query->bindValue(':age', $this->age, PDO::PARAM_INT);
        $query->bindValue(':gender', $this->gender, PDO::PARAM_STR);
        $query->bindValue(':questionid', $this->questionid, PDO::PARAM_INT);
        $query->bindValue(':answer', $this->answer, PDO::PARAM_STR);
        $query->execute();
        $this->id = $this->dbConnection->lastInsertId();
    }
    
    
    /**
     * Method selects whole answer by `Research ID`.
     * 
     * @param int
     * @throws WrongIDException when there is no `Research` content matched with 'Research ID`
     */
    public function setResearchById($id) {
        
        $query = $this->dbConnection->prepare('SELECT `id`, `nickname`, `age`, `gender`, `questionid`, `answer` FROM `research` WHERE `id` = :id');
        $query->bindValue(':id',$id, PDO::PARAM_INT);
        $query->execute();
        $rows = $query->rowCount();
        if ($rows <= 0) {
            throw new WrongIDException();
        }
        $result = $query->fetch();
        $this->id = $result['id'];
        $this->nickname = $result['nickname'];
        $this->age = $result['age'];
        $this->gender = $result['gender'];
        $this->questionid = $result['questionid'];
        $this->answer = $result['answer'];
    }
    
    
    /**
     * Method deletes current answer.
     * 
     * @throws DeleteException when `Research ID` is wrong.
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
        $this->content = '';
        $this->date = '';
        $this->authorid = '';
        $this->pageid = '';
    }
    
    
    
    public function getId() {
        return $this->id;
    }
    
    public function getNickname() {
        return $this->nickname;
    }
    
    public function getAge() {
        return $this->age;
    }
    
    public function getGender() {
        return $this->gender;
    }
    
    public function getQuestionId() {
        return $this->questionid;
    }
    
    public function getAnswer() {
        return $this->answer;
    }
}
