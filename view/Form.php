<?php

/**
 * Class that prepares all HTML forms.
 * 
 * @author Bartosz Studnik
 */
class Form {
    
    /**
     * @var array
     */
    private $fieldsArray;
    
    /**
     * ex. `do=action`
     * @var string 
     */
    private $action;
    
    /**
     * Data send method
     * ex. GET
     * @var string
     */
    private $method;
    
    /**
     * @var string 
     */
    private $submitMessage;
    
    public function __construct($fieldsArray, $action = '', $method = 'GET', $submitMessage = SUBMIT_TEXT) {
        
        $this->fieldsArray = $fieldsArray;
        $this->action = $action;
        $this->method = $method;
        $this->submitMessage = $submitMessage;
    }
    
    
    /**
     * Method that creates a HTML form.
     */
    public function createForm() {
        
        $toReturn = "<form method=\"$this->method\" action=\"$this->action\">";
        foreach($this->fieldsArray as $field) {
            if($field["type"] == 'select') {
                $toReturn .= $this->select($field["options"], $field["label"], $field["name"], $field["id"]);
            } else if($field["type"] == 'textarea') {
                $toReturn .= $this->textarea($field["label"], $field["name"], $field["id"], $field["value"]);
            } else {
                if(isset($field["readonly"])) {
                    $readonly = true;
                } else {
                    $readonly = false;
                }
                $toReturn .= $this->input($field["label"], $field["type"], $field["name"], $field["id"], $readonly, $field["value"]);
            }
        }
        $toReturn.= '<input type="submit" value="'.$this->submitMessage.'"></form>';
        return $toReturn;
    }
    
    
    /**
     * Method that prepares HTML input.
     * It's called by createForm() method.
     * 
     * @param string
     * @param string
     * @param string
     * @param int
     * @return HTML code to print input on website.
     */
    private function input($label, $type, $name, $id, $readonly, $value) {
        if ($readonly) {
            $ro = " readonly";
        } else {
            $ro = "";
        }
        if (isset($value)) {
            $v = "value=\"$value\"";
        } else {
            $v = "";
        }
        return "<label for=\"$name\">$label</label><input type=\"$type\" name=\"$name\" $v id=\"$id\"$ro><br />";
    }
    
    
    /**
     * Method that prepares HTML select.
     * It's called by createForm() method.
     * 
     * @param array
     * @param string
     * @param string
     * @param int
     * @return HTML code to print select on website.
     */
    private function select($options, $label, $name, $id = '') {
         $toReturn = "<label for=\"$name\">$label</label><select name=\"$name\" id=\"$id\">";
         
         foreach($options as $key=>$option) {
            if (isset($option['selected'])) {
                $selected = " selected";
            } else {
                $selected = "";
            }
            $toReturn .= '<option value="'.$key.'" '.$selected.'>'.$option['value'].'</option>';
         }
         $toReturn .= '</select><br />';
         return $toReturn;
    }
    
    
    /**
     * Static method that prepares HTML radio button for Quiz sub-page.
     * 
     * @param string
     * @return string
     */
    public static function quizRadio($name) {
        return '<input type="radio" name="'.$name.'" value="1">'.ANSWER_ONE.'<br><input type="radio" name="'.$name.'" value="2">'.ANSWER_TWO.'<br>';
    }
    
    
    /**
     * Method that prepares HTML textarea.
     * It's called by createForm() method.
     * 
     * @param string
     * @param string
     * @param string
     * @param string
     * @return string
     */
    private function textarea($label, $name, $id, $value) {
        if (isset($value)) {
            $v = $value;
        } else {
            $v = "";
        }
        return "<label for=\"$name\">$label</label><textarea name=\"$name\" id=\"$id\" rows=\"12\" cols=\"100\">$v</textarea><br />";
    }
}
