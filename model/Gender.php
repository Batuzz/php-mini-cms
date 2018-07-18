<?php

/**
 * It's responsible for changing numeric `gender` from database into proper string in adequate language.
 * 
 * @author Bartosz Studnik
 */
class Gender {
    
    /**
     * @var int 
     */
    private $genderId;
    
    /**
     * @var string 
     */
    private $gender;
    
    
    
    /**
     * Constructor with one parameter. It initializes `genderId` and sets properly `gender` string.
     * 
     * @param int
     */
    public function __construct($genderId) {
        $this->genderId = $genderId;
        $this->setGenderById();
    }
    
    
    /**
     * Changes numeric `genderId` into a string (`gender`).
     */
    private function setGenderById() {
        
        if($this->genderId == 0) {
            $this->gender = SEX_FEMALE;
        } else {
            $this->gender = SEX_MALE;
        }
    }
    
    
    
    public function getGender() {
        return $this->gender;
    }
}
