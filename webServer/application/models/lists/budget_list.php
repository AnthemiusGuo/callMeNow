<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/budget_model.php");
class Budget_list extends List_model {
    public function __construct() {
        parent::__construct("fBudget");
        parent::init("Budget_list","Budget_model");
        
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