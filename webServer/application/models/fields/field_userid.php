<?php
include_once(APPPATH."models/fields/field_relate_simple_id.php");

class Field_userid extends Field_relate_simple_id {
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_userid";
        $this->set_relate_db('uUser','_id','name');
    }
    public function init($value){
        
        if (is_numeric($value) && $value<=0){
            $this->userName = '[系统]';
        } else {
            parent::init($value);
        }
    }
    public function gen_list_html(){
        return $this->userName;
    }
    public function gen_show_html(){
        return $this->userName;
    }
    public function gen_search_element($default="="){
        $editor = "<input type=\"hidden\" id=\"searchEle_{$this->name}\" name=\"search_{$this->name}\" class=\"form-control input-sm\" value=\"=\">";
        $editor .= "=";
        return $editor;
    }
    
}
?>