<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/pay_model.php");
class Pay_list extends List_model {
    public function __construct() {
        parent::__construct('bPay');
        parent::init("Pay_list","Pay_model");
    }
    
    public function build_search_infos(){
        return array('money','method','status','payTS','typ');
    }
    
    public function build_inline_list_titles(){
    	return array('money','method','status','payTS');
    }
    public function build_list_titles(){
        return array('crmId','money','method','status','payTS','typ');
    }
}
?>