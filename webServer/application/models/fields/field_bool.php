<?php
include_once(APPPATH."models/fields/field_enum.php");
class Field_bool extends Field_enum {
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_sex";
        $this->setEnum(array(
            '否',
            '是'
            ));
    }
}
?>