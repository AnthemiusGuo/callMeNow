<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/turnovertype_model.php");
class Turnovertype_list extends List_model {
    public function __construct() {
        parent::__construct('fTurnoverType');
        parent::init("Turnovertype_list","Turnovertype_model");
    }

    public function init(){
        for ($i=0;$i<35;$i++){
            $this->record_list[$i] = new Turnovertype_model();
            $this->record_list[$i]->init($i);
        }
       
    }

    public function build_list_titles(){
        return array('name','typ');
    }
}
?>