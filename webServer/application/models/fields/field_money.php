<?php
include_once(APPPATH."models/fields/field_int.php");
class Field_money extends Field_int {
	public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_enum";
    }
    private function __calc_money(){
    	return number_format($this->value);
    }
    private function __calc_cn_money(){
    }

    public function gen_show_html(){
        return $this->__calc_money();//." (".$this->__calc_cn_money().")";
    }
    public function gen_list_html($len=16){
        return $this->__calc_money();
    }
}