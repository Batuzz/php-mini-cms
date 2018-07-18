<?php
require_once('exceptions/NoDataException.php');

/**
 * This controller is responsible for communication with Database.
 * 
 * @author Bartosz Studnik
 */
class DatabaseController {
    
    /**
     * @var string
     */
    private $ip;
    
    /**
     * @var int
     */
    private $port;
    
    /**
     * @var string
     */
    private $username;
    
    /**
     * @var string
     */
    private $password;
    
    /**
     * Database `name`
     * @var string
     */
    private $name;
    
    /**
     * @var Database Connection
     */
    private $connection;
    
    
    
    /**
     * Constructor with no parameters. It creates Database Connecton.
     */
    public function __construct() {
        
        $this->ip='localhost';
        $this->port='3306';
        $this->username='root';
        $this->password='';
        $this->name='konkurs';
        
        try {
            $this->connection = new PDO('mysql:host='.$this->ip.';dbname='.$this->name.';port='.$this->port, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo DATABASE_CONNECTION_ERROR.'<br />';
        }
    }
    
    
    /**
     * Method that selects whole users data from Database.
     * 
     * @return usersQuery
     * @throws NoDataException when there is no data in Database.
     */
    public function selectUsers() {
        $query =  $this->connection->query('SELECT `id`, `name`, `date`, `access` FROM `users`');       
        if ($query->rowCount() == 0) {
            throw new NoDataException();
        }
        return $query;
    }
    
    
    /**
     * Method that selects whole `content` data from Database.
     * It's connected with `pages` table.
     * 
     * @return contentsQuery
     * @throws NoDataException when there is no data in Database.
     */
    public function selectContent() {
        $query = $this->connection->query('SELECT `contents`.`id`, `title`, `titleen`, `content`, `contenten`, `date`, `authorid`, `pageid`, `pages`.`name` AS `pageName` FROM `contents` JOIN `pages` ON `pages`.id = `contents`.`pageid`'); 
        if ($query->rowCount() == 0) {
            throw new NoDataException();
        }
        return $query;
    }
    
    
    /**
     * Method that selects `content` data from Database by `ID`.
     * 
     * @param int
     * @return contentsQuery
     * @throws NoDataException when there is no data in Database.
     */
    public function selectContentsByPageId($pageId) {
        $query = $this->connection->prepare('SELECT `id`, `title`, `content`, `date`, `authorid`, `pageid` FROM `contents` WHERE pageid = :pageId'); 
        $query->bindValue(':pageId', $pageId, PDO::PARAM_INT);
        $query->execute();
        if ($query->rowCount() == 0) {
            throw new NoDataException();
        }
        return $query;
    }
    
    
    /**
     * Method that selects whole pages data from Database.
     * It's connected with `pages` table.
     * 
     * @return pagesQuery
     * @throws NoDataException when there is no data in Database.
     */
    public function selectPages() {
        $query = $this->connection->query('SELECT `pages`.`id`, `pages`.`name`, `pages`.`nameen`, `pages`.`sectionid`, `menu`.`name` AS `menuName` FROM `pages` JOIN `menu` ON `pages`.sectionid = `menu`.`id`');
        if ($query->rowCount() == 0) {
            throw new NoDataException();
        }
        return $query;
    }
    
    
    /**
     * Method that selects whole menu data from Database.
     * 
     * @return menuQuery
     * @throws NoDataException when there is no data in Database.
     */
    public function selectMenu() {
        $query =  $this->connection->query('SELECT `id`, `name`, `nameen`, `address`, `isMenu`, `order` FROM `menu`');
        if ($query->rowCount() == 0) {
            throw new NoDataException();
        }
        return $query;
    }
    
    
    /**
     * Method that selects `Categories` data from Database.
     * 
     * @return categoriesQuery
     * @throws NoDataException when there is no data in Database.
     */
    public function selectCategories() {
        $query =  $this->connection->query('SELECT `id`, `name`, `nameen`, `address`, `isMenu`, `order` FROM `menu` WHERE `isMenu` = 1');
        
        if ($query->rowCount() == 0) {
            throw new NoDataException();
        }
        return $query;
    }
    
    
    /**
     * Method that selects `Questions` data from Database.
     * 
     * @return questionsQuery
     * @throws NoDataException when there is no data in Database.
     */
    public function selectQuestions() {
        $query =  $this->connection->query('SELECT `id`, `content`, `contenten`, `answer1`, `answer1en`, `answer2`, `answer2en` FROM `questions`');
        if ($query->rowCount() == 0) {
            throw new NoDataException();
        }
        return $query;
    }
    
    public function selectResearches() {
        $query =  $this->connection->query('SELECT `questionid`, `answer`, COUNT(*) AS `a` FROM `research` GROUP BY `questionid`, `answer`');
        if ($query->rowCount() == 0) {
            throw new NoDataException();
        }
        return $query;
    }
    
    
    
    public function getConnection() {
        return $this->connection;
    }
}