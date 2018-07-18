<?php
require_once('exceptions/UpdateException.php');

/**
 * Class manages menu section.
 *
 * @author Bartosz Studnik
 */
class Menu {

    /**
     * @var Database Connection 
     */
    private $dbConnection;
    
    /**
     * Menu ID
     * @var int 
     */
    private $id;
    
    /**
     * Menu name
     * @var string 
     */
    private $name;
    
    /**
     * English menu name
     * @var string 
     */
    private $nameen;
    
    /**
     * Menu `href` address
     * @var string 
     */
    private $address;
    
    /**
     * Menu type
     * @var boolean 
     */
    private $isMenu;
    
    /**
     * Menu order
     * @var int
     */
    private $order;
    
    
    
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
        $this->address = '';
        $this->isMenu = false;
        $this->order = '';
    }
    
    
    /**
     * Sets current menu data.
     * 
     * @param string
     * @param string
     * @param int
     * @param int
     * @param int
     */
    public function setMenu($name, $nameen, $address, $isMenu, $order, $id = 0) {
        
        $this->id = $id;
        $this->name = $name;
        $this->nameen = $nameen;
        $this->address = $address;
        $this->isMenu = $isMenu;
        $this->order = $order;
    }
    
    
    /**
     * Method inserts current menu into Database.
     * Menu needs to be set before.
     */
    public function insert() {
        
        $query = $this->dbConnection->prepare("INSERT INTO `menu` (`id`, `name`, `nameen`, `address`, `isMenu`, `order`) VALUES (NULL, :name, :nameen, :address, :isMenu, :order)");
        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        $query->bindValue(':nameen', $this->nameen, PDO::PARAM_STR);
        $query->bindValue(':address', $this->address, PDO::PARAM_STR);
        $query->bindValue(':isMenu', $this->isMenu, PDO::PARAM_STR);
        $query->bindValue(':order', $this->order, PDO::PARAM_STR);
        $query->execute();
        $this->id = $this->dbConnection->lastInsertId();
    }
    
    
    /**
     * Method updates current menu.
     * 
     * @throws UpdateException when `Menu ID` is wrong.
     */
    public function update() {
        
        if($this->id <=0) {
            throw new UpdateException();
        }
        $query = $this->dbConnection->prepare('UPDATE `menu` SET `name` = :name, `nameen` = :nameen, `address`= :address, `isMenu`= :isMenu, `order`= :order WHERE id = :id');
        $query->bindValue(':id', $this->id, PDO::PARAM_STR);
        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        $query->bindValue(':nameen', $this->nameen, PDO::PARAM_STR);
        $query->bindValue(':address', $this->address, PDO::PARAM_STR);
        $query->bindValue(':isMenu', $this->isMenu, PDO::PARAM_STR);
        $query->bindValue(':order', $this->order, PDO::PARAM_STR);
        $query->execute();
        $this->id = $this->dbConnection->lastInsertId();
    }
    
    
    /**
     * Method selects whole menu by `Menu ID`.
     * 
     * @param int
     * @throws WrongIDException when `Menu ID` is wrong.
     */
    public function setMenuById($id) {
       
        $query = $this->dbConnection->prepare('SELECT `id`, `name`, `nameen`, `address`, `isMenu`, `order` FROM `menu` WHERE `id` = :id');
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
        $this->address = $result['address'];
        $this->isMenu = $result['isMenu'];
        $this->order = $result['order'];
    }
    
    
     /**
     * Method deletes current row.
     * 
     * @throws DeleteException when `Menu ID` is wrong.
     */
    public function delete() {
        
        if($this->id <=0) {
            throw new DeleteException();
        }
        $query = $this->dbConnection->prepare('DELETE FROM `menu` WHERE id = :id');
        $query->bindValue(':id', $this->id, PDO::PARAM_STR);
        $query->execute();
        $this->id = '';
        $this->name = '';
        $this->address = '';
        $this->isMenu = false;
        $this->order = '';
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
    
    public function getAddress(){
        return $this->address;
    }
    
    public function getIsMenu(){        
        return $this->isMenu;
    }
    
    public function getOrder(){        
        return $this->order;
    }
}