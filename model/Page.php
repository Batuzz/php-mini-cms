<?php

/**
 * Class that manages Pages section
 *
 * @author Bartosz Studnik
 */
class Page {

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
    private $name;
    
    /**
     * @var string 
     */
    private $nameen;
    
    /**
     * @var int 
     */
    private $sectionid;
    
    
    
    /**
     * Constructor with one parameter. Connects with Database.
     * 
     * @param Database connection
     */
    public function __construct($db) {
        
        $this->dbConnection = $db->getConnection();
        $this->id = '';
        $this->name = '';
        $this->nameen = '';
        $this->sectionid = '';
    }
    
    
    /**
     * Sets current page data.
     * 
     * @param string
     * @param int
     * @param int
     */
    public function setPage($name, $nameen, $sectionid, $id=0) {
        
        $this->id = $id;
        $this->name = $name;
        $this->nameen = $nameen;
        $this->sectionid = $sectionid;
    }
    
    
    /**
     * Method inserts current page into Database.
     * Page needs to be set before.
     */
    public function insert() {
        
        $query = $this->dbConnection->prepare("INSERT INTO `pages` (`id`, `name`, `nameen`, `sectionid`) VALUES (NULL, :name, :nameen, :sectionid)");
        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        $query->bindValue(':nameen', $this->nameen, PDO::PARAM_STR);
        $query->bindValue(':sectionid', $this->sectionid, PDO::PARAM_STR);
        $query->execute();
        $this->id = $this->dbConnection->lastInsertId();
    }
    
    
    /**
     * Method updates current page.
     * 
     * @throws UpdateException when `Page ID` is wrong.
     */
    public function update() {
        
        if($this->id <=0) {
            throw new UpdateException();
        }
        $query = $this->dbConnection->prepare('UPDATE `pages` SET `name` = :name, `nameen` = :nameen, `sectionid`= :sectionid WHERE id = :id');
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        $query->bindValue(':nameen', $this->nameen, PDO::PARAM_STR);
        $query->bindValue(':sectionid', $this->sectionid, PDO::PARAM_STR);
        $query->execute();
        $this->id = $this->dbConnection->lastInsertId();
    }
    
    
    /**
     * Method selects whole page by `Page ID`.
     * 
     * @param int
     * @throws WrongIDException when there is no Page content matched with 'Page ID`
     */
    public function setPageById($id) {
       
        $query = $this->dbConnection->prepare('SELECT `id`, `name`, `nameen`, `sectionid` FROM `pages` WHERE `id` = :id');
        $query->bindValue(':id',$id, PDO::PARAM_INT);        
        $query->execute();
        $rows = $query->rowCount();
        if ($rows <= 0) {
            throw new WrongIDException();
        }
        $result = $query->fetch();
        $this->id = $result['id'];
        $this->name = $result['name'];
        $this->nameen = $result['nameen'];
        $this->sectionid = $result['sectionid'];
    }
    
    /**
     * Method deletes whole page.
     * 
     * @throws DeleteException when when `Page ID` is wrong.
     */
    public function delete() {
        
        if($this->id <=0) {
            throw new DeleteException();
        }
        $query = $this->dbConnection->prepare('DELETE FROM `pages` WHERE id = :id');
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->execute();
        
        $this->id = '';
        $this->name = '';
        $this->sectionid = '';
    }
    
    
    
    public function getId(){        
        return $this->id;
    }
    
    public function getName(){        
        return $this->name;
    }
    
    public function getNameen(){        
        return $this->nameen;
    }
    
    public function getSectionid(){        
        return $this->sectionid;
    }
}