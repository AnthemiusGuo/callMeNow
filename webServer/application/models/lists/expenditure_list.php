<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/expenditure_model.php");


class Expenditure_list extends List_model {
    public function __construct() {
        parent::__construct("fExpenditure");
        parent::init("Expenditure_list","Expenditure_model");
    }
    public function init_by_project($projectId=0){
        for ($i=0;$i<35;$i++){
            $this->record_list[$i] = new Expenditure_model();
            $this->record_list[$i]->init($i);
        }
       
    }
    public function build_inline_list_titles(){
        return array('showId','desc','typ','totalPrice','bookkeeper','approveUid','status');
    }
    public function build_short_list_titles(){
        return array('showId','desc');
    }
    public function build_list_titles(){
        return array('showId','desc','typ');
    }
}
?>