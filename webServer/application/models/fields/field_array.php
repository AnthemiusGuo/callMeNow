<?php
include_once(APPPATH."models/fields/fields.php");
class Field_array extends Fields {
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_array";
    }
    public function init($value){
        parent::init((int)$value);
    }
    public function gen_value($input){
        $input = (int)$input;
    
        return $input;
    }
    public function build_validator(){
        $validater = ' digits ';
        if ($this->is_must_input){
            $validater .= ' min="1" ';
        }
        $validater .= parent::build_validator();
        return $validater;
    }
    public function gen_editor($typ=0){
        $inputName = $this->build_input_name($typ);
        $validates = $this->build_validator();
        if ($typ==1){
            $this->default = $this->value;
        }
        return "<input id=\"{$inputName}\" name=\"{$inputName}\" class=\"{$this->input_class}\" placeholder=\"{$this->placeholder}\" type=\"text\" value=\"{$this->default}\" $validates /> ";
    }
    public function check_data_input($input)
    {
        if ($input==0){
            return false;
        }
        return parent::check_data_input($input);
    }
}
?>